<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Subject;
use App\Services\TimeOverrideService;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    // Menampilkan halaman scanner/QR generator
    public function showScanner()
    {
        $teacherId = Auth::id();
        $dayOfWeek = TimeOverrideService::dayOfWeek();

        $jadwalQuery = Timetable::with(['classSubject.subject', 'classSubject.class'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // Group by class_subject_id and type to merge consecutive times
        $grouped = $jadwalQuery->groupBy(function ($item) {
            return $item->class_subject_id . '-' . ($item->type ?? 'teori');
        });

        $jadwalHariIni = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Merge consecutive times
            $mergedTimes = [];
            $currentStart = null;
            $currentEnd = null;

            foreach ($sortedGroup as $jadwal) {
                if ($currentStart === null) {
                    $currentStart = $jadwal->start_time;
                    $currentEnd = $jadwal->end_time;
                } elseif ($jadwal->start_time === $currentEnd) {
                    $currentEnd = $jadwal->end_time;
                } else {
                    $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
                    $currentStart = $jadwal->start_time;
                    $currentEnd = $jadwal->end_time;
                }
            }
            if ($currentStart !== null) {
                $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
            }

            // Create entries for each merged time
            foreach ($mergedTimes as $time) {
                $firstJadwal = $sortedGroup->first();
                $jadwalHariIni->push((object)[
                    'id' => $firstJadwal->id, // Use first id for QR generation
                    'start_time' => $time['start'],
                    'end_time' => $time['end'],
                    'classSubject' => $firstJadwal->classSubject
                ]);
            }
        }

        return view('guru.scan-qr', compact('jadwalHariIni'));
    }

    // Generate QR Code yang disederhanakan
    public function generateQrCode(Request $request)
    {
        try {
            Log::info('generateQrCode called with data:', $request->all());
            
            // Simple validation first
            if (!$request->has('timetable_id')) {
                return response()->json(['error' => 'timetable_id is required'], 400);
            }
            
            $timetableId = $request->input('timetable_id');
            Log::info('timetable_id received: ' . $timetableId);
            
            $request->validate([
                'timetable_id' => 'required|exists:timetables,id',
            ]);

            $timetable = Timetable::with('classSubject.subject', 'classSubject.class')->find($request->timetable_id);
            
            if (!$timetable) {
                Log::error('Timetable not found with ID: ' . $request->timetable_id);
                return response()->json(['error' => 'Timetable tidak ditemukan dengan ID: ' . $request->timetable_id], 404);
            }
            $user = Auth::user()->load('teacher');

            if (!$user->teacher) {
                return response()->json(['error' => 'Data guru (NIP) tidak ditemukan.'], 404);
            }

        // Pastikan ada ClassSession untuk hari ini
        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $timetable->id, 'date' => TimeOverrideService::today()],
            ['status' => 'ongoing', 'opened_by' => $user->id]
        );

        // Generate session token yang unik
        $sessionToken = md5($timetable->id . $user->id . time() . rand(1000, 9999));
        
        // Waktu expire 2 jam
        $expiresAt = now()->addHours(2);
        
        // Buat data QR yang disederhanakan - hanya field essential
        $qrData = [
            'session_id' => $sessionToken,
            'timetable_id' => $timetable->id,
            'teacher_id' => $user->id,
            'checksum' => hash('sha256', $sessionToken . $timetable->id . $user->id)
        ];

        // Simpan session ke database
        $attendanceSession = AttendanceSession::create([
            'timetable_id' => $timetable->id,
            'teacher_id' => $user->id,
            'session_number' => 1,
            'session_token' => $sessionToken,
            'qr_data' => $qrData,
            'session_type' => 'on_time', // Default, akan ditentukan saat scan
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);
        
        Log::info('Attendance session created successfully:', $attendanceSession->toArray());

        return response()->json($qrData);
        
        } catch (\Exception $e) {
            Log::error('Error generating QR Code: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat membuat QR Code: ' . $e->getMessage()], 500);
        }
    }


    // Memproses scan dengan sistem multi-session
    public function processScan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|array',
            'student_id' => 'required|exists:students,user_id',
        ]);

        $qrData = $request->qr_data;
        $studentId = $request->student_id;

        // Validasi format QR data
        if (!$this->validateQRFormat($qrData)) {
            return response()->json(['error' => 'Invalid QR format'], 400);
        }

        // Cek session masih aktif
        $session = AttendanceSession::where('session_token', $qrData['session_id'])
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session expired atau tidak aktif'], 400);
        }

        // Validasi checksum
        $expectedChecksum = hash('sha256', 
            $qrData['session_id'] . $qrData['timetable_id'] . $qrData['teacher_id']
        );

        if ($qrData['checksum'] !== $expectedChecksum) {
            return response()->json(['error' => 'Invalid QR data'], 400);
        }

        // Cek apakah siswa sudah pernah absen di session ini
        $existingRecord = Attendance::where('student_id', $studentId)
            ->where('timetable_id', $qrData['timetable_id'])
            ->where('session_id', $session->id)
            ->first();

        if ($existingRecord) {
            return response()->json(['error' => 'Sudah absen di session ini'], 400);
        }

        // Tentukan status berdasarkan waktu scan
        $timetable = Timetable::findOrFail($qrData['timetable_id']);
        $classStartTime = Carbon::parse($timetable->start_time);
        $currentTime = now();
        $lateMinutes = $currentTime->diffInMinutes($classStartTime);

        // Tentukan status berdasarkan waktu
        if ($lateMinutes <= 15) {
            $status = 'H'; // Hadir tepat waktu
            $isOnTime = true;
        } elseif ($lateMinutes <= 30) {
            $status = 'H'; // Hadir dengan toleransi
            $isOnTime = false;
        } else {
            $status = 'T'; // Terlambat
            $isOnTime = false;
        }

        // Pastikan ada ClassSession
        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $qrData['timetable_id'], 'date' => TimeOverrideService::today()],
            ['status' => 'ongoing', 'opened_by' => $qrData['teacher_id']]
        );

        // Simpan record absensi
        $attendance = Attendance::create([
            'class_session_id' => $classSession->id,
            'student_id' => $studentId,
            'session_id' => $session->id,
            'session_number' => 1,
            'status' => $status,
            'check_in_time' => $currentTime->format('H:i:s'),
            'is_on_time' => $isOnTime,
            'late_minutes' => $lateMinutes,
            'notes' => $isOnTime ? null : 'Terlambat ' . $lateMinutes . ' menit',
        ]);

        $student = Student::where('user_id', $studentId)->with('user')->first();

        return response()->json([
            'success' => true,
            'status' => $status,
            'student_name' => optional($student->user)->full_name ?? 'Siswa Tidak Ditemukan',
            'student_nis' => optional($student)->nis ?? '-',
            'check_in_time' => $attendance->check_in_time,
            'is_on_time' => $isOnTime,
            'late_minutes' => $lateMinutes,
        ]);
    }

    // Helper method untuk validasi format QR
    private function validateQRFormat($qrData)
    {
        $requiredFields = ['session_id', 'timetable_id', 'teacher_id', 'checksum'];
        
        foreach ($requiredFields as $field) {
            if (!isset($qrData[$field])) {
                return false;
            }
        }
        
        return true;
    }

    // Mengembalikan hasil pindaian untuk ditampilkan guru (format yang mudah dirender)
    public function getScanResults($timetable_id)
    {
        $classSession = ClassSession::where('timetable_id', $timetable_id)
                                    ->where('date', TimeOverrideService::today())
                                    ->first();

        if (!$classSession) {
            return response()->json([]);
        }

        $attendances = Attendance::with(['student.user'])
                        ->where('class_session_id', $classSession->id)
                        ->orderBy('id')
                        ->get();

        $rows = $attendances->map(function ($a, $i) {
            return [
                'no' => $i + 1,
                'student_name' => optional($a->student->user)->full_name ?? '-',
                'student_nisn' => optional($a->student)->nis ?? '-',
                'check_in_time' => $a->check_in_time,
                'check_out_time' => $a->check_out_time,
                'note' => $a->notes,
                'status' => $a->status,
            ];
        })->values();

        return response()->json($rows);
    }

    // Stop session QR Code
    public function stopSession(Request $request)
    {
        try {
            Log::info('Stop session request received:', $request->all());
            
            $request->validate([
                'session_token' => 'required|string',
            ]);

            $sessionToken = $request->session_token;
            $teacherId = Auth::id();
            
            Log::info('Looking for session with token:', ['token' => $sessionToken]);
            Log::info('Teacher ID:', ['teacher_id' => $teacherId]);
            Log::info('Teacher ID type:', ['type' => gettype($teacherId)]);

            // Check if teacher exists in teachers table
            $teacherExists = \App\Models\Teacher::where('user_id', $teacherId)->exists();
            Log::info('Teacher exists in teachers table:', ['exists' => $teacherExists]);

            // Check if attendance_sessions table exists and is accessible
            try {
                $tableExists = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='attendance_sessions'");
                Log::info('attendance_sessions table exists:', ['exists' => !empty($tableExists)]);
            } catch (\Exception $e) {
                Log::error('Error checking table existence:', ['error' => $e->getMessage()]);
            }

            $session = AttendanceSession::where('session_token', $sessionToken)
                ->where('teacher_id', $teacherId)
                ->first();
                
            Log::info('Session query result:', ['session' => $session ? $session->toArray() : null]);

            if (!$session) {
                Log::error('Session not found for token:', ['token' => $sessionToken]);
                return response()->json(['error' => 'Session tidak ditemukan'], 404);
            }

            Log::info('Session found, deactivating:', ['session' => $session->toArray()]);
            
            // Try to deactivate the session
            try {
                $session->deactivate();
                Log::info('Session deactivated successfully');
            } catch (\Exception $deactivateError) {
                Log::error('Error during deactivation:', [
                    'message' => $deactivateError->getMessage(),
                    'file' => $deactivateError->getFile(),
                    'line' => $deactivateError->getLine()
                ]);
                throw $deactivateError;
            }

            return response()->json(['success' => true, 'message' => 'Session berhasil dihentikan']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in stopSession:', $e->errors());
            return response()->json(['error' => 'Data tidak valid: ' . implode(', ', $e->errors()['session_token'] ?? [])], 422);
        } catch (\Exception $e) {
            Log::error('Error in stopSession:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    // Menampilkan halaman status absensi (existing)
    public function showStatus(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjectId = $request->input('subject_id');
        $selectedDate = $request->input('date', TimeOverrideService::today());

        $query = Attendance::with(['student.user', 'classSession.timetable.classSubject.subject'])
            ->whereHas('classSession', function ($q) use ($selectedDate) {
                $q->where('date', $selectedDate);
            });

        if ($selectedSubjectId) {
            $query->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
                $q->where('subject_id', $selectedSubjectId);
            });
        }

        $attendances = $query->latest('id')->get();

        return view('guru.status-absensi', compact('subjects', 'attendances', 'selectedSubjectId', 'selectedDate'));
    }
}