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
use App\Models\SessionDelegation;
use App\Models\Subject;
use App\Models\Classroom;
use App\Services\TimeOverrideService;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    // Menampilkan halaman scanner/QR generator
    public function showScanner(Request $request)
    {
        $teacherId = Auth::id();
        $dayOfWeek = TimeOverrideService::dayOfWeek();

        // Check if there's a timetable_id parameter (for delegation)
        $timetableId = $request->input('timetable_id');
        
        // Get regular teacher schedules
        $jadwalQuery = Timetable::with(['classSubject.subject', 'classSubject.class'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();
        
        // Get delegated schedules for this teacher
        $delegatedSchedules = SessionDelegation::with(['timetable.classSubject.subject', 'timetable.classSubject.class', 'timetable' => function($query) {
                $query->where('day_of_week', TimeOverrideService::dayOfWeek());
            }])
            ->where('delegated_to_user_id', $teacherId)
            ->where('status', 'active')
            ->where(function($query) {
                $query->where('type', 'permanent')
                      ->orWhere(function($q) {
                          $q->where('type', 'temporary')
                            ->where('valid_until', '>=', TimeOverrideService::today());
                      });
            })
            ->get()
            ->filter(function($delegation) {
                return $delegation->timetable && $delegation->timetable->day_of_week === TimeOverrideService::dayOfWeek();
            })
            ->map(function($delegation) {
                return $delegation->timetable;
            });
        
        // Merge regular and delegated schedules
        $allSchedules = $jadwalQuery->merge($delegatedSchedules)->unique('id');

        // Group by class_subject_id and type to merge consecutive times
        $grouped = $allSchedules->groupBy(function ($item) {
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

        // Data rekap riwayat absensi hari ini
        $rekapRiwayat = $this->getRekapRiwayatAbsensi($teacherId);

        return view('guru.scan-qr', compact('jadwalHariIni', 'rekapRiwayat'));
    }

    // Method untuk mendapatkan rekap riwayat absensi hari ini
    private function getRekapRiwayatAbsensi($teacherId)
    {
        try {
            $today = TimeOverrideService::today();
            
            // Ambil semua jadwal guru hari ini
            $dayOfWeek = TimeOverrideService::dayOfWeek();
            $timetablesToday = Timetable::with(['classSubject.subject', 'classSubject.class', 'classSubject.teacher'])
                ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                    $query->where('user_id', $teacherId);
                })
                ->where('day_of_week', $dayOfWeek)
                ->get();

            // Group by class_subject_id to merge duplicate subjects in same class
            $grouped = $timetablesToday->groupBy(function ($item) {
                return $item->class_subject_id;
            });

            $rekapData = collect();

            foreach ($grouped as $group) {
                // Sort by start_time
                $sortedGroup = $group->sortBy('start_time');

                // Get earliest start time and latest end time
                $earliestStart = $sortedGroup->first()->start_time;
                $latestEnd = $sortedGroup->last()->end_time;

                // Use first timetable as base for data
                $firstTimetable = $sortedGroup->first();

                // Ambil data absensi untuk semua jadwal dalam grup ini hari ini
                $allAttendances = collect();
                foreach ($sortedGroup as $timetable) {
                    $attendances = Attendance::with(['student.user'])
                        ->whereHas('classSession', function($query) use ($timetable, $today) {
                            $query->where('timetable_id', $timetable->id)
                                  ->where('date', $today);
                        })
                        ->get();
                    $allAttendances = $allAttendances->merge($attendances);
                }

                // Hitung statistik dari semua absensi dalam grup
                $totalStudents = Student::where('class_id', $firstTimetable->classSubject->class_id)->count();
                $hadir = $allAttendances->where('status', 'H')->count();
                $terlambat = $allAttendances->where('status', 'T')->count();
                $izin = $allAttendances->where('status', 'I')->count();
                $sakit = $allAttendances->where('status', 'S')->count();
                $alpa = $totalStudents - $hadir - $terlambat - $izin - $sakit;
                
                // Hitung persentase kehadiran
                $persentase = $totalStudents > 0 ? round((($hadir + $terlambat) / $totalStudents) * 100, 1) : 0;

                $rekapData->push([
                    'timetable_id' => $firstTimetable->id,
                    'mata_pelajaran' => $firstTimetable->classSubject->subject->name ?? 'N/A',
                    'kelas' => $firstTimetable->classSubject->class->name ?? 'N/A',
                    'jam' => Carbon::parse($earliestStart)->format('H:i') . ' - ' . Carbon::parse($latestEnd)->format('H:i'),
                    'total_siswa' => $totalStudents,
                    'hadir' => $hadir,
                    'terlambat' => $terlambat,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'persentase' => $persentase,
                    'status_badge' => $this->getStatusBadge($persentase)
                ]);
            }

            return $rekapData->sortBy('jam');
        } catch (\Exception $e) {
            Log::error('Error getting rekap riwayat absensi: ' . $e->getMessage());
            return collect();
        }
    }

    // Helper method untuk menentukan warna badge berdasarkan persentase
    private function getStatusBadge($persentase)
    {
        if ($persentase >= 90) {
            return 'bg-success-subtle text-success';
        } elseif ($persentase >= 75) {
            return 'bg-warning-subtle text-warning';
        } else {
            return 'bg-danger-subtle text-danger';
        }
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

            $timetable = Timetable::with('classSubject.subject', 'classSubject.class', 'classSubject.teacher.user')->find($request->timetable_id);
            
            if (!$timetable) {
                Log::error('Timetable not found with ID: ' . $request->timetable_id);
                return response()->json(['error' => 'Timetable tidak ditemukan dengan ID: ' . $request->timetable_id], 404);
            }
            
            $user = Auth::user()->load('teacher');

            // CEK APAKAH USER INI DAPAT DELEGASI?
            $delegation = \App\Models\SessionDelegation::where('timetable_id', $timetable->id)
                ->where('delegated_to_user_id', $user->id)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->where('type', 'permanent')
                          ->orWhere(function($q) {
                              $q->where('type', 'temporary')
                                ->where('valid_until', '>=', now()->toDateString());
                          });
                })
                ->first();

            // Jika bukan guru dan tidak ada delegasi, tolak
            if (!$user->teacher && !$delegation) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk membuka QR ini.'], 403);
            }

            // Tentukan flags
            $isDelegated = $delegation ? true : false;
            $originalTeacherId = $timetable->classSubject->teacher->user_id;
            $openedByUserId = $user->id;
            $delegationReason = $delegation ? $delegation->admin_notes : null;

        // Pastikan ada ClassSession untuk hari ini
        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $timetable->id, 'date' => TimeOverrideService::today()],
            ['status' => 'ongoing', 'opened_by' => $openedByUserId]
        );

        // Generate session token yang unik
        $sessionToken = md5($timetable->id . $user->id . TimeOverrideService::timestamp() . rand(1000, 9999));
        
        // Waktu expire 2 jam
        $expiresAt = TimeOverrideService::now()->addHours(2);
        
        // Buat data QR yang disederhanakan - hanya field essential
        $qrData = [
            'session_id' => $sessionToken,
            'timetable_id' => $timetable->id,
            'teacher_id' => $originalTeacherId,
            'checksum' => hash('sha256', $sessionToken . $timetable->id . $originalTeacherId)
        ];

        // Simpan session ke database
        $attendanceSession = AttendanceSession::create([
            'timetable_id' => $timetable->id,
            'teacher_id' => $originalTeacherId, // Guru asli tetap
            'opened_by_user_id' => $openedByUserId, // NEW
            'is_delegated' => $isDelegated, // NEW
            'delegation_reason' => $delegationReason, // NEW
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
            ->where('expires_at', '>', TimeOverrideService::now())
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
        $currentTime = TimeOverrideService::now();
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

            // Cari session berdasarkan token saja terlebih dahulu
            $session = AttendanceSession::where('session_token', $sessionToken)->first();

            Log::info('Session query result:', ['session' => $session ? $session->toArray() : null]);

            if (!$session) {
                Log::error('Session not found for token:', ['token' => $sessionToken]);
                return response()->json(['error' => 'Session tidak ditemukan'], 404);
            }

            // Otorisasi:
            // - Guru asli (teacher_id) boleh menghentikan
            // - Pengguna yang membuka (opened_by_user_id) juga boleh menghentikan (kasus delegasi)
            $isOwnerTeacher = ((int)$session->teacher_id === (int)$teacherId);
            $isOpenedByUser = ((int)$session->opened_by_user_id === (int)$teacherId);

            if (!$isOwnerTeacher && !$isOpenedByUser) {
                Log::warning('Unauthorized stopSession attempt', [
                    'session_teacher_id' => $session->teacher_id,
                    'opened_by_user_id' => $session->opened_by_user_id,
                    'request_user_id' => $teacherId,
                ]);
                return response()->json(['error' => 'Anda tidak berhak menghentikan sesi ini'], 403);
            }

            Log::info('Session authorized for deactivation, proceeding...', ['session_id' => $session->id]);

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
        // Get logged in teacher
        $teacherId = Auth::id();
        
        // Get only subjects that the teacher teaches
        $subjects = Subject::whereHas('classSubjects.teacher', function($q) use ($teacherId) {
            $q->where('user_id', $teacherId);
        })->orderBy('name')->get();
        
        // Get only classrooms that the teacher teaches
        $classrooms = Classroom::whereHas('classSubjects.teacher', function($q) use ($teacherId) {
            $q->where('user_id', $teacherId);
        })->orderBy('grade')->orderBy('name')->get();
        
        $selectedSubjectId = $request->input('subject_id');
        $selectedClassroomId = $request->input('classroom_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Query dasar dengan semua relasi yang diperlukan
        // Hanya ambil attendance dari kelas yang diajarkan oleh guru yang login
        $query = Attendance::with([
            'student.user',
            'student.classroom',
            'classSession.timetable.classSubject.subject',
            'classSession.timetable.classSubject.class',
            'classSession.timetable.classSubject.teacher'
        ])->whereHas('classSession.timetable.classSubject.teacher', function($q) use ($teacherId) {
            $q->where('user_id', $teacherId);
        });

        // Filter berdasarkan rentang tanggal
        if ($dateFrom && $dateTo) {
            $query->whereHas('classSession', function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date', [$dateFrom, $dateTo]);
            });
        } elseif ($dateFrom) {
            // Jika hanya date_from, tampilkan data pada tanggal tersebut
            $query->whereHas('classSession', function ($q) use ($dateFrom) {
                $q->where('date', $dateFrom);
            });
        }

        // Filter berdasarkan mata pelajaran jika dipilih
        if ($selectedSubjectId) {
            $query->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
                $q->where('subject_id', $selectedSubjectId);
            });
        }

        // Filter berdasarkan kelas jika dipilih
        if ($selectedClassroomId) {
            $query->whereHas('student', function ($q) use ($selectedClassroomId) {
                $q->where('class_id', $selectedClassroomId);
            });
        }

        $attendances = $query->latest('id')->get();

        return view('guru.status-absensi', compact(
            'subjects', 
            'classrooms',
            'attendances', 
            'selectedSubjectId',
            'selectedClassroomId',
            'dateFrom',
            'dateTo'
        ));
    }
}