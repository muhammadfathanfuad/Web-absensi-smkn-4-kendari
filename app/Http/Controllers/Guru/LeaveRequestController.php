<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Timetable;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display leave requests for today's classes
     */
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek;
        
        // Get today's timetables for this teacher
        $todayTimetables = Timetable::with(['classSubject.subject', 'classSubject.class', 'classSubject.teacher'])
            ->whereHas('classSubject', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('day_of_week', $dayOfWeek)
            ->get();

        // Get all students from today's classes
        $studentIds = collect();
        foreach ($todayTimetables as $timetable) {
            $classStudents = Student::where('class_id', $timetable->classSubject->class->id)->pluck('user_id');
            $studentIds = $studentIds->merge($classStudents);
        }

        // Get leave requests for today's students
        $leaveRequests = LeaveRequest::with(['student', 'processedBy'])
            ->whereIn('student_id', $studentIds->unique())
            ->where(function($query) use ($today) {
                $query->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by student and add class information
        $leaveRequestsWithClass = $leaveRequests->map(function($request) use ($todayTimetables) {
            $student = $request->student;
            $classes = collect();
            
            foreach ($todayTimetables as $timetable) {
                $classStudents = Student::where('class_id', $timetable->classSubject->class->id)->pluck('user_id');
                if ($classStudents->contains($student->id)) {
                    $classes->push([
                        'class_name' => $timetable->classSubject->class->name,
                        'subject_name' => $timetable->classSubject->subject->name,
                        'time_start' => $timetable->time_start,
                        'time_end' => $timetable->time_end
                    ]);
                }
            }
            
            $request->classes = $classes;
            return $request;
        });

        return view('guru.permohonan-izin', compact('leaveRequestsWithClass', 'todayTimetables'));
    }

    /**
     * Show leave request details
     */
    public function show($id)
    {
        Log::info('=== SHOW METHOD CALLED ===');
        Log::info('ID: ' . $id);
        Log::info('Request URL: ' . request()->url());
        Log::info('Request Method: ' . request()->method());
        
        try {
            $leaveRequest = LeaveRequest::with(['student', 'processedBy'])
                ->findOrFail($id);

            Log::info('Leave request found: ' . $leaveRequest->id);

            // Transform the data to match what the frontend expects
            $data = $leaveRequest->toArray();
            
            // Add name attribute for student
            if ($leaveRequest->student) {
                $data['student']['name'] = $leaveRequest->student->full_name;
            }
            
            // Add name attribute for processedBy
            if ($leaveRequest->processedBy) {
                $data['processed_by']['name'] = $leaveRequest->processedBy->full_name;
            }

            // Add teacher approval/rejection information
            $data['teacher_status'] = [
                'approved_by' => $leaveRequest->approved_by_teachers ?? [],
                'rejected_by' => $leaveRequest->rejected_by_teachers ?? [],
                'overall_status' => $leaveRequest->overall_status ?? 'pending'
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data.'], 500);
        }
    }

    /**
     * Approve leave request
     */
    public function approve(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // Check if teacher has access to this request
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        // Check if teacher can still take action on this request
        if (!$leaveRequest->canTeacherTakeAction($teacher->user_id)) {
            if ($leaveRequest->isApprovedByTeacher($teacher->user_id)) {
                return response()->json(['error' => 'Anda sudah menyetujui permohonan ini sebelumnya.'], 403);
            } else if ($leaveRequest->isRejectedByTeacher($teacher->user_id)) {
                return response()->json(['error' => 'Anda sudah menolak permohonan ini sebelumnya.'], 403);
            }
        }

        // Check if teacher teaches the student's class
        $student = Student::where('user_id', $leaveRequest->student_id)->first();
        if (!$student) {
            return response()->json(['error' => 'Data siswa tidak ditemukan.'], 404);
        }
        
        // Check if teacher has access to student's class (any day, not just today)
        $hasAccess = Timetable::whereHas('classSubject', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->user_id);
            })
            ->whereHas('classSubject', function($query) use ($student) {
                $query->where('class_id', $student->class_id);
            })
            ->exists();

        if (!$hasAccess) {
            return response()->json(['error' => 'Anda tidak mengajar di kelas siswa ini.'], 403);
        }

        // Add teacher approval
        $leaveRequest->addTeacherApproval($teacher->user_id);
        $leaveRequest->processed_by = $user->id;
        $leaveRequest->processed_at = now();
        $leaveRequest->admin_notes = $request->notes;
        $leaveRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Permohonan izin berhasil disetujui.'
        ]);
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // Check if teacher has access to this request
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        // Check if teacher can still take action on this request
        if (!$leaveRequest->canTeacherTakeAction($teacher->user_id)) {
            if ($leaveRequest->isApprovedByTeacher($teacher->user_id)) {
                return response()->json(['error' => 'Anda sudah menyetujui permohonan ini sebelumnya.'], 403);
            } else if ($leaveRequest->isRejectedByTeacher($teacher->user_id)) {
                return response()->json(['error' => 'Anda sudah menolak permohonan ini sebelumnya.'], 403);
            }
        }

        // Check if teacher teaches the student's class
        $student = Student::where('user_id', $leaveRequest->student_id)->first();
        if (!$student) {
            return response()->json(['error' => 'Data siswa tidak ditemukan.'], 404);
        }
        
        // Check if teacher has access to student's class (any day, not just today)
        $hasAccess = Timetable::whereHas('classSubject', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->user_id);
            })
            ->whereHas('classSubject', function($query) use ($student) {
                $query->where('class_id', $student->class_id);
            })
            ->exists();

        if (!$hasAccess) {
            return response()->json(['error' => 'Anda tidak mengajar di kelas siswa ini.'], 403);
        }

        // Add teacher rejection
        $leaveRequest->addTeacherRejection($teacher->user_id);
        $leaveRequest->processed_by = $user->id;
        $leaveRequest->processed_at = now();
        $leaveRequest->admin_notes = $request->notes;
        $leaveRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Permohonan izin berhasil ditolak untuk mata pelajaran Anda.'
        ]);
    }
}
