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
use App\Models\Term;
use App\Services\TimeOverrideService;
use App\Services\AttendanceCacheService;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // Pastikan semua ID adalah integer untuk konsistensi
        $qrData = [
            'session_id' => $sessionToken,
            'timetable_id' => (int) $timetable->id,
            'teacher_id' => (int) $originalTeacherId,
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
        
        Log::info('Attendance session created successfully:', [
            'session_id' => $attendanceSession->id,
            'timetable_id' => $attendanceSession->timetable_id,
            'session_token' => $sessionToken,
            'qr_data' => $qrData
        ]);

        // SOLUSI MASALAH 1: Jika yang membuka QR adalah siswa delegasi, otomatis catat absensinya
        if ($isDelegated && !$user->teacher) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                // Pastikan siswa terdaftar di kelas yang sesuai
                $timetableClassId = $timetable->classSubject?->class_id;
                if (isset($student->class_id) && $timetableClassId && $student->class_id == $timetableClassId) {
                    // Cek apakah sudah ada absensi untuk siswa ini di session ini
                    $existingAttendance = Attendance::where('student_id', $user->id)
                        ->where('class_session_id', $classSession->id)
                        ->first();

                    if (!$existingAttendance) {
                        // Tentukan status berdasarkan waktu
                        $timezone = 'Asia/Makassar';
                        $jamMasuk = Carbon::parse($timetable->start_time, $timezone);
                        $jamScan = TimeOverrideService::now()->setTimezone($timezone);
                        
                        $status = 'H';
                        $note = 'Hadir (sebagai delegasi)';
                        $isOnTime = true;
                        $lateMinutes = 0;

                        if ($jamScan->isAfter($jamMasuk)) {
                            $lateMinutes = round($jamScan->diffInMinutes($jamMasuk));
                            if ($lateMinutes > 15) {
                                $status = 'T';
                                $note = 'Terlambat ' . $lateMinutes . ' menit (sebagai delegasi)';
                                $isOnTime = false;
                            } else {
                                $note = 'Hadir tepat waktu (sebagai delegasi)';
                            }
                        }

                        // Check for leave request
                        $today = TimeOverrideService::today();
                        $leaveRequestStatus = \App\Http\Controllers\Guru\LeaveRequestController::checkLeaveRequestForAttendance(
                            $user->id,
                            $timetable->id,
                            $today
                        );

                        if ($leaveRequestStatus) {
                            $status = $leaveRequestStatus;
                            $note = $status === 'I' 
                                ? 'Izin (disetujui oleh guru) - sebagai delegasi' 
                                : 'Alpha (permohonan izin ditolak) - sebagai delegasi';
                            $isOnTime = false;
                        }

                        // Catat absensi siswa delegasi
                        Attendance::create([
                            'class_session_id' => $classSession->id,
                            'student_id' => $user->id,
                            'session_id' => $attendanceSession->id,
                            'session_number' => 1,
                            'status' => $status,
                            'check_in_time' => TimeOverrideService::now()->format('H:i:s'),
                            'is_on_time' => $isOnTime,
                            'late_minutes' => $lateMinutes,
                            'notes' => $note,
                        ]);

                        Log::info('Auto attendance recorded for student delegate:', [
                            'student_id' => $user->id,
                            'timetable_id' => $timetable->id,
                            'status' => $status
                        ]);
                    }
                }
            }
        }

        // Return dengan JSON_FORCE_OBJECT untuk memastikan format konsisten
        return response()->json($qrData, 200, [], JSON_NUMERIC_CHECK);
        
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

        // Check for leave request first (for future dates or if student has approved/rejected leave request)
        $today = TimeOverrideService::today();
        $leaveRequestStatus = \App\Http\Controllers\Guru\LeaveRequestController::checkLeaveRequestForAttendance(
            $studentId,
            $qrData['timetable_id'],
            $today
        );

        // If there's a leave request decision, use that status
        if ($leaveRequestStatus) {
            $status = $leaveRequestStatus;
            $isOnTime = false;
            $notes = $status === 'I' 
                ? 'Izin (disetujui oleh guru)' 
                : 'Alpha (permohonan izin ditolak)';
        } else {
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
            $notes = $isOnTime ? null : 'Terlambat ' . $lateMinutes . ' menit';
        }

        // Pastikan ada ClassSession
        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $qrData['timetable_id'], 'date' => $today],
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
            'notes' => $notes,
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
        
        // Get cached subjects and classrooms (optimized with cache)
        $subjects = AttendanceCacheService::getTeacherSubjects($teacherId);
        $classrooms = AttendanceCacheService::getTeacherClassrooms($teacherId);
        
        $selectedSubjectId = $request->input('subject_id');
        $selectedClassroomId = $request->input('classroom_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Determine default period preset based on current month
        // If no preset is provided, default to current semester
        $now = Carbon::now();
        $defaultPreset = 'custom';
        if (!$request->has('period_preset') && !$dateFrom && !$dateTo) {
            // Auto-detect current semester based on month
            if ($now->month >= 7 && $now->month <= 12) {
                $defaultPreset = 'semester_ganjil';
            } elseif ($now->month >= 1 && $now->month <= 6) {
                $defaultPreset = 'semester_genap';
            }
        }
        
        $periodPreset = $request->input('period_preset', $defaultPreset);
        $viewType = $request->input('view_type', 'summary'); // Default to 'summary' for better UX

        // Handle period preset
        $dateFromCarbon = null;
        $dateToCarbon = null;
        
        if ($periodPreset !== 'custom') {
            switch ($periodPreset) {
                case 'semester_ganjil':
                    // Juli - Desember
                    $year = $now->year;
                    if ($now->month >= 7) {
                        $dateFromCarbon = Carbon::create($year, 7, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year, 12, 31)->endOfDay();
                    } else {
                        $dateFromCarbon = Carbon::create($year - 1, 7, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year - 1, 12, 31)->endOfDay();
                    }
                    break;
                case 'semester_genap':
                    // Januari - Juni
                    $year = $now->year;
                    if ($now->month >= 1 && $now->month <= 6) {
                        $dateFromCarbon = Carbon::create($year, 1, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year, 6, 30)->endOfDay();
                    } else {
                        $dateFromCarbon = Carbon::create($year, 1, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year, 6, 30)->endOfDay();
                    }
                    break;
                case 'bulan_ini':
                    $dateFromCarbon = $now->copy()->startOfMonth()->startOfDay();
                    $dateToCarbon = $now->copy()->endOfMonth()->endOfDay();
                    break;
            }
            
            if ($dateFromCarbon && $dateToCarbon) {
                $dateFrom = $dateFromCarbon->format('Y-m-d');
                $dateTo = $dateToCarbon->format('Y-m-d');
            }
        } else {
            if ($dateFrom) {
                $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
            }
            if ($dateTo) {
                $dateToCarbon = Carbon::parse($dateTo)->endOfDay();
            }
        }

        // Query dasar dengan semua relasi yang diperlukan
        // Hanya ambil attendance dari kelas yang diajarkan oleh guru yang login
        // Optimasi: Select kolom spesifik untuk mengurangi memory usage
        $query = Attendance::with([
            'student:user_id,nis,class_id', // FIXED: students table uses user_id as primary key, not id
            'student.user:id,full_name',
            'student.classroom:id,grade,name',
            'classSession:id,timetable_id,date',
            'classSession.timetable:id,class_subject_id',
            'classSession.timetable.classSubject:id,subject_id,class_id,teacher_id',
            'classSession.timetable.classSubject.subject:id,name',
            'classSession.timetable.classSubject.class:id,grade,name',
            'classSession.timetable.classSubject.teacher:user_id' // FIXED: teachers table uses user_id as primary key, not id
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

        // Optimasi: Gunakan pagination untuk data besar (50 items per page)
        $perPage = 50;
        $attendances = $query->latest('attendances.id')->paginate($perPage)->withQueryString();

        // Calculate summary if view type is summary
        // Untuk summary, kita perlu query terpisah tanpa pagination untuk kalkulasi yang akurat
        $summary = [];
        if ($viewType === 'summary') {
            // Query terpisah untuk summary (tanpa pagination)
            $summaryQuery = Attendance::with([
                'student:user_id,nis,class_id', // FIXED: students table uses user_id as primary key, not id
                'student.user:id,full_name',
                'student.classroom:id,grade,name'
            ])->whereHas('classSession.timetable.classSubject.teacher', function($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            });

            // Apply same filters
            if ($dateFrom && $dateTo) {
                $summaryQuery->whereHas('classSession', function ($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('date', [$dateFrom, $dateTo]);
                });
            } elseif ($dateFrom) {
                $summaryQuery->whereHas('classSession', function ($q) use ($dateFrom) {
                    $q->where('date', $dateFrom);
                });
            }

            if ($selectedSubjectId) {
                $summaryQuery->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
                    $q->where('subject_id', $selectedSubjectId);
                });
            }

            if ($selectedClassroomId) {
                $summaryQuery->whereHas('student', function ($q) use ($selectedClassroomId) {
                    $q->where('class_id', $selectedClassroomId);
                });
            }

            // Process dengan chunking untuk summary
            $summaryData = [];
            $summaryQuery->latest('attendances.id')->chunk(500, function ($attendances) use (&$summaryData) {
                foreach ($attendances as $attendance) {
                    $studentId = $attendance->student_id;
                    if (!isset($summaryData[$studentId])) {
                        $summaryData[$studentId] = [
                            'student_id' => $studentId,
                            'nis' => $attendance->student->nis ?? 'N/A',
                            'name' => $attendance->student->user->full_name ?? 'N/A',
                            'class' => $attendance->student->classroom 
                                ? ($attendance->student->classroom->grade . ' - ' . $attendance->student->classroom->name)
                                : 'N/A',
                            'total_hadir' => 0,
                            'total_terlambat' => 0,
                            'total_absen' => 0,
                            'total_izin' => 0,
                            'total_sakit' => 0,
                            'total_pertemuan' => 0,
                        ];
                    }
                    
                    $status = $attendance->status;
                    if ($status == 'H') {
                        $summaryData[$studentId]['total_hadir']++;
                    } elseif ($status == 'T') {
                        $summaryData[$studentId]['total_terlambat']++;
                    } elseif ($status == 'A') {
                        $summaryData[$studentId]['total_absen']++;
                    } elseif ($status == 'I') {
                        $summaryData[$studentId]['total_izin']++;
                    } elseif ($status == 'S') {
                        $summaryData[$studentId]['total_sakit']++;
                    }
                    $summaryData[$studentId]['total_pertemuan']++;
                }
            });

            // Calculate percentage
            foreach ($summaryData as &$student) {
                $student['persentase'] = $student['total_pertemuan'] > 0 
                    ? round(($student['total_hadir'] / $student['total_pertemuan']) * 100, 2) 
                    : 0;
            }
            
            $summary = array_values($summaryData);
        }

        return view('guru.status-absensi', compact(
            'subjects', 
            'classrooms',
            'attendances', 
            'summary',
            'selectedSubjectId',
            'selectedClassroomId',
            'dateFrom',
            'dateTo',
            'periodPreset',
            'viewType'
        ));
    }

    /**
     * Calculate attendance summary per student
     */
    private function calculateAttendanceSummary($attendances, $selectedSubjectId = null)
    {
        $summary = [];
        
        foreach ($attendances as $attendance) {
            $studentId = $attendance->student_id;
            $studentName = $attendance->student->user->full_name ?? 'N/A';
            $studentNis = $attendance->student->nis ?? 'N/A';
            $className = $attendance->student->classroom 
                ? ($attendance->student->classroom->grade . ' - ' . $attendance->student->classroom->name)
                : 'N/A';
            
            if (!isset($summary[$studentId])) {
                $summary[$studentId] = [
                    'student_id' => $studentId,
                    'nis' => $studentNis,
                    'name' => $studentName,
                    'class' => $className,
                    'total_hadir' => 0,
                    'total_terlambat' => 0,
                    'total_absen' => 0,
                    'total_izin' => 0,
                    'total_sakit' => 0,
                    'total_pertemuan' => 0,
                    'persentase' => 0
                ];
            }
            
            // Count by status
            $status = $attendance->status;
            if ($status == 'H') {
                $summary[$studentId]['total_hadir']++;
            } elseif ($status == 'T') {
                $summary[$studentId]['total_terlambat']++;
            } elseif ($status == 'A') {
                $summary[$studentId]['total_absen']++;
            } elseif ($status == 'I') {
                $summary[$studentId]['total_izin']++;
            } elseif ($status == 'S') {
                $summary[$studentId]['total_sakit']++;
            }
            
            $summary[$studentId]['total_pertemuan']++;
        }
        
        // Calculate percentage for each student
        foreach ($summary as &$student) {
            if ($student['total_pertemuan'] > 0) {
                $hadir = $student['total_hadir'] + $student['total_terlambat']; // Terlambat juga dihitung hadir
                $student['persentase'] = round(($hadir / $student['total_pertemuan']) * 100, 2);
            }
        }
        
        // Sort by name
        usort($summary, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        return $summary;
    }

    public function export(Request $request)
    {
        try {
            $teacherId = Auth::id();
            $teacher = Auth::user();
            $teacherName = $teacher->full_name ?? 'Guru';

            $selectedSubjectId = $request->input('subject_id');
            $selectedClassroomId = $request->input('classroom_id');
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            $periodPreset = $request->input('period_preset', 'custom');
            $viewType = $request->input('view_type', 'detail');

        // Handle period preset
        $dateFromCarbon = null;
        $dateToCarbon = null;
        
        if ($periodPreset !== 'custom') {
            $now = Carbon::now();
            switch ($periodPreset) {
                case 'semester_ganjil':
                    $year = $now->year;
                    if ($now->month >= 7) {
                        $dateFromCarbon = Carbon::create($year, 7, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year, 12, 31)->endOfDay();
                    } else {
                        $dateFromCarbon = Carbon::create($year - 1, 7, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year - 1, 12, 31)->endOfDay();
                    }
                    break;
                case 'semester_genap':
                    $year = $now->year;
                    if ($now->month >= 1 && $now->month <= 6) {
                        $dateFromCarbon = Carbon::create($year, 1, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year, 6, 30)->endOfDay();
                    } else {
                        $dateFromCarbon = Carbon::create($year, 1, 1)->startOfDay();
                        $dateToCarbon = Carbon::create($year, 6, 30)->endOfDay();
                    }
                    break;
                case 'bulan_ini':
                    $dateFromCarbon = $now->copy()->startOfMonth()->startOfDay();
                    $dateToCarbon = $now->copy()->endOfMonth()->endOfDay();
                    break;
            }
            
            if ($dateFromCarbon && $dateToCarbon) {
                $dateFrom = $dateFromCarbon->format('Y-m-d');
                $dateTo = $dateToCarbon->format('Y-m-d');
            }
        }

            // Query dasar dengan semua relasi yang diperlukan
            // Optimasi: Select kolom spesifik untuk mengurangi memory usage
            $baseQuery = Attendance::with([
                'student:user_id,nis,class_id', // FIXED: students table uses user_id as primary key, not id
                'student.user:id,full_name',
                'student.classroom:id,grade,name',
                'classSession:id,timetable_id,date',
                'classSession.timetable:id,class_subject_id',
                'classSession.timetable.classSubject:id,subject_id,class_id,teacher_id',
                'classSession.timetable.classSubject.subject:id,name',
                'classSession.timetable.classSubject.class:id,grade,name',
                'classSession.timetable.classSubject.teacher:user_id' // FIXED: teachers table uses user_id as primary key, not id
            ])->whereHas('classSession.timetable.classSubject.teacher', function($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            });

            // Filter berdasarkan rentang tanggal
            if ($dateFrom && $dateTo) {
                $baseQuery->whereHas('classSession', function ($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('date', [$dateFrom, $dateTo]);
                });
            } elseif ($dateFrom) {
                $baseQuery->whereHas('classSession', function ($q) use ($dateFrom) {
                    $q->where('date', $dateFrom);
                });
            }

            // Filter berdasarkan mata pelajaran jika dipilih
            if ($selectedSubjectId) {
                $baseQuery->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
                    $q->where('subject_id', $selectedSubjectId);
                });
            }

            // Filter berdasarkan kelas jika dipilih
            if ($selectedClassroomId) {
                $baseQuery->whereHas('student', function ($q) use ($selectedClassroomId) {
                    $q->where('class_id', $selectedClassroomId);
                });
            }

            // Optimasi: Gunakan chunking untuk memproses data besar per batch (500 records)
            // Ini mencegah memory overflow dan timeout
            $pdfData = [];
            $counter = 0;
            $summaryData = [];

            if ($viewType === 'summary') {
                // Untuk summary, kita perlu semua data untuk kalkulasi
                // Tapi kita tetap gunakan chunking untuk mengurangi memory
                $baseQuery->latest('attendances.id')->chunk(500, function ($attendances) use (&$summaryData) {
                    foreach ($attendances as $attendance) {
                        $studentId = $attendance->student_id;
                        if (!isset($summaryData[$studentId])) {
                            $summaryData[$studentId] = [
                                'student_id' => $studentId,
                                'nis' => $attendance->student->nis ?? 'N/A',
                                'name' => $attendance->student->user->full_name ?? 'N/A',
                                'class' => $attendance->student->classroom 
                                    ? ($attendance->student->classroom->grade . ' - ' . $attendance->student->classroom->name)
                                    : 'N/A',
                                'total_hadir' => 0,
                                'total_terlambat' => 0,
                                'total_absen' => 0,
                                'total_izin' => 0,
                                'total_sakit' => 0,
                                'total_pertemuan' => 0,
                            ];
                        }
                        
                        $status = $attendance->status;
                        if ($status == 'H') {
                            $summaryData[$studentId]['total_hadir']++;
                        } elseif ($status == 'T') {
                            $summaryData[$studentId]['total_terlambat']++;
                        } elseif ($status == 'A') {
                            $summaryData[$studentId]['total_absen']++;
                        } elseif ($status == 'I') {
                            $summaryData[$studentId]['total_izin']++;
                        } elseif ($status == 'S') {
                            $summaryData[$studentId]['total_sakit']++;
                        }
                        $summaryData[$studentId]['total_pertemuan']++;
                    }
                });

                // Calculate percentage and prepare PDF data
                foreach ($summaryData as $student) {
                    $persentase = $student['total_pertemuan'] > 0 
                        ? round(($student['total_hadir'] / $student['total_pertemuan']) * 100, 2) 
                        : 0;
                    
                    $pdfData[] = [
                        'no' => count($pdfData) + 1,
                        'nis' => $student['nis'],
                        'nama' => $student['name'],
                        'kelas' => $student['class'],
                        'total_hadir' => $student['total_hadir'],
                        'total_terlambat' => $student['total_terlambat'],
                        'total_absen' => $student['total_absen'],
                        'total_izin' => $student['total_izin'],
                        'total_sakit' => $student['total_sakit'],
                        'total_pertemuan' => $student['total_pertemuan'],
                        'persentase' => $persentase,
                    ];
                }
            } else {
                // Detail view - process dengan chunking
                $baseQuery->latest('attendances.id')->chunk(500, function ($attendances) use (&$pdfData, &$counter) {
                    foreach ($attendances as $absen) {
                $status = 'N/A';
                if ($absen->status == 'S') {
                    $status = 'Sakit';
                } elseif ($absen->status == 'I') {
                    $status = 'Izin';
                } elseif ($absen->status == 'T' || ($absen->notes === 'Terlambat' && $absen->status !== 'H')) {
                    $status = 'Terlambat';
                } elseif ($absen->status == 'H') {
                    $status = 'Hadir';
                }

                    $tanggal = '-';
                    if ($absen->classSession && $absen->classSession->date) {
                        $tanggal = Carbon::parse($absen->classSession->date)->translatedFormat('d/m/Y');
                    }

                $pdfData[] = [
                            'no' => ++$counter,
                        'tanggal' => $tanggal,
                    'nis' => $absen->student->nis ?? 'N/A',
                    'nama' => $absen->student->user->full_name ?? 'N/A',
                            'kelas' => $absen->student->classroom 
                                ? $absen->student->classroom->grade . ' - ' . $absen->student->classroom->name 
                                : 'N/A',
                    'mapel' => $absen->classSession->timetable->classSubject->subject->name ?? 'N/A',
                    'jam_masuk' => $absen->check_in_time ?? '-',
                    'status' => $status,
                ];
                }
                });
            }

            // Build filter info
            $filterInfo = [];
            if ($periodPreset !== 'custom') {
                $presetText = match($periodPreset) {
                    'semester_ganjil' => 'Semester Ganjil',
                    'semester_genap' => 'Semester Genap',
                    'bulan_ini' => 'Bulan Ini',
                    default => 'Custom'
                };
                $filterInfo[] = 'Periode: ' . $presetText;
            }
            if ($selectedSubjectId) {
                $subject = Subject::find($selectedSubjectId);
                if ($subject) {
                    $filterInfo[] = 'Mata Pelajaran: ' . $subject->name;
                }
            }
            if ($selectedClassroomId) {
                $classroom = Classroom::find($selectedClassroomId);
                if ($classroom) {
                    $filterInfo[] = 'Kelas: ' . $classroom->grade . ' - ' . $classroom->name;
                }
            }
            if ($dateFrom && $dateTo) {
                $filterInfo[] = 'Dari Tanggal: ' . Carbon::parse($dateFrom)->translatedFormat('d F Y');
                $filterInfo[] = 'Sampai Tanggal: ' . Carbon::parse($dateTo)->translatedFormat('d F Y');
            } elseif ($dateFrom) {
                $filterInfo[] = 'Tanggal: ' . Carbon::parse($dateFrom)->translatedFormat('d F Y');
            }
            if (empty($filterInfo)) {
                $filterInfo[] = 'Semua Data';
            }

            $filename = ($viewType === 'summary' ? 'ringkasan_' : 'rekap_') . 'kehadiran_siswa_' . date('YmdHis') . '.pdf';

            $pdf = Pdf::loadView('guru.status-absensi-pdf', [
                'attendances' => $pdfData,
                'teacherName' => $teacherName,
                'filterInfo' => $filterInfo,
                'viewType' => $viewType,
            ]);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Export attendance history error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengexport data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}