<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\Student;
use App\Services\TimeOverrideService;

class ScanController extends Controller
{
    // Murid mengirimkan scan ke endpoint ini (POST)
    public function submit(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Student QR scan request received:', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $request->validate([
            'timetable_id' => 'required|exists:timetables,id',
            'session_token' => 'nullable|string', // Optional for backward compatibility
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            Log::error('Student not found for user_id: ' . $user->id);
            return response()->json(['success' => false, 'message' => 'Data murid tidak ditemukan.'], 404);
        }

        Log::info('Student found:', ['student_id' => $student->id, 'class_id' => $student->class_id]);

        $timetable = Timetable::with(['classSubject.subject', 'classSubject.class'])->findOrFail($request->timetable_id);

        // Validasi: pastikan murid terdaftar di kelas yang sesuai
        $timetableClassId = $timetable->classSubject?->class_id;
        if (isset($student->class_id) && $timetableClassId && $student->class_id != $timetableClassId) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar pada kelas ini.'], 403);
        }

        // If session_token is provided, validate the attendance session
        if ($request->session_token) {
            Log::info('Validating session token:', ['session_token' => $request->session_token]);
            
            $attendanceSession = \App\Models\AttendanceSession::where('session_token', $request->session_token)
                ->where('is_active', true)
                ->where('expires_at', '>', TimeOverrideService::now())
                ->first();

            if (!$attendanceSession) {
                Log::warning('Session not found or expired:', [
                    'session_token' => $request->session_token,
                    'current_time' => TimeOverrideService::now()->toISOString()
                ]);
                return response()->json(['success' => false, 'message' => 'Sesi absensi sudah berakhir atau tidak aktif.'], 400);
            }

            Log::info('Session found:', ['session_id' => $attendanceSession->id, 'timetable_id' => $attendanceSession->timetable_id]);

            // Check if student already attended this session
            $existingAttendance = Attendance::where('student_id', $user->id)
                ->where('session_id', $attendanceSession->id)
                ->first();

            if ($existingAttendance) {
                Log::info('Student already attended this session:', ['attendance_id' => $existingAttendance->id]);
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absensi untuk sesi ini.'], 409);
            }
        }

        $classSession = ClassSession::firstOrCreate(
            ['timetable_id' => $timetable->id, 'date' => TimeOverrideService::today()],
            ['status' => 'ongoing', 'opened_by' => $timetable->classSubject?->teacher?->user_id ?? null]
        );

        // Check for existing attendance in this class session
        $attendance = Attendance::where('class_session_id', $classSession->id)
                                ->where('student_id', $user->id)
                                ->first();
        
        if ($attendance) {
            // already checked in? do check-out
            if ($attendance->check_out_time !== null) {
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-out untuk kelas ini.'], 409);
            }
            
            $attendance->check_out_time = TimeOverrideService::now()->format('H:i:s');
            $attendance->save();
            
            Log::info('Check-out successful:', [
                'attendance_id' => $attendance->id,
                'student_id' => $attendance->student_id,
                'check_out_time' => $attendance->check_out_time,
                'check_in_time' => $attendance->check_in_time
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Check-out berhasil! Anda sudah keluar dari kelas.',
                'status' => $attendance->status,
                'check_in_time' => $attendance->check_in_time,
                'check_out_time' => $attendance->check_out_time
            ]);
        }

        // Enhanced status logic based on scan time
        $timezone = 'Asia/Makassar';
        $jamMasuk = Carbon::parse($timetable->start_time, $timezone);
        $jamSelesai = Carbon::parse($timetable->end_time, $timezone);
        $jamScan = TimeOverrideService::now()->setTimezone($timezone);

        // Determine status based on scan time
        $status = 'A'; // Default: Tidak Hadir
        $note = null;
        $isOnTime = false;
        $lateMinutes = 0;

        if ($jamScan->isBefore($jamMasuk)) {
            // Scan sebelum jam masuk (tidak mungkin, tapi untuk safety)
            $status = 'H';
            $note = 'Hadir lebih awal';
            $isOnTime = true;
        } elseif ($jamScan->between($jamMasuk, $jamMasuk->copy()->addMinutes(15))) {
            // Scan dalam 15 menit pertama setelah jam masuk
            $status = 'H';
            $note = 'Hadir tepat waktu';
            $isOnTime = true;
        } elseif ($jamScan->between($jamMasuk->copy()->addMinutes(15), $jamSelesai)) {
            // Scan setelah 15 menit tapi sebelum jam selesai
            $lateMinutes = round($jamScan->diffInMinutes($jamMasuk));
            $status = 'T';
            $note = 'Terlambat ' . $lateMinutes . ' menit';
            $isOnTime = false;
        } else {
            // Scan setelah jam selesai
            $lateMinutes = round($jamScan->diffInMinutes($jamMasuk));
            $status = 'T';
            $note = 'Terlambat ' . $lateMinutes . ' menit (setelah jam selesai)';
            $isOnTime = false;
        }

        $attendanceData = [
            'class_session_id' => $classSession->id,
            'student_id' => $user->id,
            'status' => $status,
            'check_in_time' => $jamScan->format('H:i:s'),
            'notes' => $note,
            'is_on_time' => $isOnTime,
            'late_minutes' => $lateMinutes,
        ];

        // Add session_id if available
        if ($request->session_token && isset($attendanceSession)) {
            $attendanceData['session_id'] = $attendanceSession->id;
            $attendanceData['session_number'] = 1;
        }

        $attendance = Attendance::create($attendanceData);

        Log::info('Attendance created successfully:', [
            'attendance_id' => $attendance->id,
            'student_id' => $attendance->student_id,
            'status' => $attendance->status,
            'check_in_time' => $attendance->check_in_time,
            'session_id' => $attendance->session_id ?? null
        ]);

        // Determine status text for response
        $statusText = match($status) {
            'H' => 'Hadir',
            'T' => 'Terlambat',
            'A' => 'Tidak Hadir',
            default => 'Unknown'
        };

        return response()->json([
            'success' => true, 
            'message' => 'Check-in berhasil! Anda sudah masuk ke kelas. Status: ' . $statusText, 
            'status' => $attendance->status,
            'status_text' => $statusText,
            'check_in_time' => $attendance->check_in_time,
            'check_out_time' => null,
            'notes' => $attendance->notes,
            'is_on_time' => $isOnTime,
            'late_minutes' => round($lateMinutes),
            'class_start_time' => $jamMasuk->format('H:i'),
            'class_end_time' => $jamSelesai->format('H:i'),
            'scan_time' => $jamScan->format('H:i')
        ]);
    }

    // Function to mark absent students after class ends
    public function markAbsentStudents($timetableId, $classSessionId)
    {
        $timetable = Timetable::with('classSubject.class.students')->findOrFail($timetableId);
        $classSession = ClassSession::findOrFail($classSessionId);
        
        // Get all students in the class
        $allStudents = $timetable->classSubject->class->students;
        
        // Get students who already have attendance records
        $attendedStudents = Attendance::where('class_session_id', $classSessionId)
            ->pluck('student_id')
            ->toArray();
        
        // Find students who haven't attended
        $absentStudents = $allStudents->whereNotIn('user_id', $attendedStudents);
        
        // Create attendance records for absent students
        foreach ($absentStudents as $student) {
            Attendance::create([
                'class_session_id' => $classSessionId,
                'student_id' => $student->user_id,
                'status' => 'A', // Absent
                'check_in_time' => null,
                'notes' => 'Tidak hadir - tidak melakukan scan',
                'is_on_time' => false,
                'late_minutes' => 0,
            ]);
        }
        
        Log::info('Marked absent students:', [
            'timetable_id' => $timetableId,
            'class_session_id' => $classSessionId,
            'total_students' => $allStudents->count(),
            'attended_students' => count($attendedStudents),
            'absent_students' => $absentStudents->count()
        ]);
        
        return $absentStudents->count();
    }

    // Get attendance history for student
    public function getAttendanceHistory()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Data murid tidak ditemukan.'], 404);
        }

        // Get today's attendances
        $attendances = Attendance::with(['classSession.timetable.classSubject.subject', 'classSession.timetable.classSubject.class'])
            ->whereHas('classSession', function ($query) {
                $query->where('date', TimeOverrideService::today());
            })
            ->where('student_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format attendance data
        $formattedAttendances = $attendances->map(function ($attendance) {
            $timetable = $attendance->classSession->timetable;
            $subject = $timetable->classSubject->subject ?? null;
            $class = $timetable->classSubject->class ?? null;
            
            // Format check-in and check-out times properly
            $checkInTime = $attendance->check_in_time ? 
                \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-';
            $checkOutTime = $attendance->check_out_time ? 
                \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-';
            
            return [
                'id' => $attendance->id,
                'subject_name' => $subject ? $subject->name : '-',
                'class_name' => $class ? $class->name : '-',
                'time_range' => $timetable ? 
                    \Carbon\Carbon::parse($timetable->start_time)->format('H:i') . ' - ' . 
                    \Carbon\Carbon::parse($timetable->end_time)->format('H:i') : '-',
                'status' => $attendance->status,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'notes' => $attendance->notes,
                'is_on_time' => $attendance->is_on_time ?? false,
                'late_minutes' => round($attendance->late_minutes ?? 0),
            ];
        });

        // Calculate summary
        $summary = [
            'hadir' => $attendances->where('status', 'H')->count(),
            'terlambat' => $attendances->where('status', 'T')->count(),
            'tidak_hadir' => $attendances->where('status', 'A')->count(),
            'izin' => $attendances->where('status', 'I')->count(),
            'sakit' => $attendances->where('status', 'S')->count(),
            'total' => $attendances->count()
        ];

        return response()->json([
            'success' => true,
            'attendances' => $formattedAttendances,
            'summary' => $summary
        ]);
    }
}
