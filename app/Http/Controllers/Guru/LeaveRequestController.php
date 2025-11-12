<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveRequestTeacherNote;
use App\Models\Timetable;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\Student;
use App\Services\TimeOverrideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
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

            // Add full URL for supporting document if it exists
            if ($leaveRequest->supporting_document) {
                // Use route to view document (with proper headers to display, not download)
                $data['document_url'] = route('guru.permohonan-izin.document', ['id' => $leaveRequest->id]);
                Log::info('Document URL: ' . $data['document_url']);
            }

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
            ->with('classSubject.subject')
            ->first();

        if (!$hasAccess) {
            return response()->json(['error' => 'Anda tidak mengajar di kelas siswa ini.'], 403);
        }

        // Add teacher approval
        $leaveRequest->addTeacherApproval($teacher->user_id);
        $leaveRequest->processed_by = $user->id;
        $leaveRequest->processed_at = now();
        $leaveRequest->admin_notes = $request->notes;
        $leaveRequest->save();

        // Save teacher note
        LeaveRequestTeacherNote::create([
            'leave_request_id' => $leaveRequest->id,
            'teacher_id' => $teacher->user_id,
            'subject_id' => $hasAccess->classSubject->subject_id,
            'action' => 'approve',
            'note' => $request->notes
        ]);

        // Record attendance as 'Izin' (I) for this teacher's subject
        $this->recordAttendanceForLeaveRequest($leaveRequest, $teacher, $hasAccess, 'I');

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
            ->with('classSubject.subject')
            ->first();

        if (!$hasAccess) {
            return response()->json(['error' => 'Anda tidak mengajar di kelas siswa ini.'], 403);
        }

        // Add teacher rejection
        $leaveRequest->addTeacherRejection($teacher->user_id);
        $leaveRequest->processed_by = $user->id;
        $leaveRequest->processed_at = now();
        $leaveRequest->admin_notes = $request->notes;
        $leaveRequest->save();

        // Save teacher note
        LeaveRequestTeacherNote::create([
            'leave_request_id' => $leaveRequest->id,
            'teacher_id' => $teacher->user_id,
            'subject_id' => $hasAccess->classSubject->subject_id,
            'action' => 'reject',
            'note' => $request->notes
        ]);

        // Record attendance as 'Alpha' (A) for this teacher's subject
        $this->recordAttendanceForLeaveRequest($leaveRequest, $teacher, $hasAccess, 'A');

        return response()->json([
            'success' => true,
            'message' => 'Permohonan izin berhasil ditolak untuk mata pelajaran Anda.'
        ]);
    }

    /**
     * Record attendance for leave request (Izin or Alpha)
     * This method records attendance for past dates and today.
     * For future dates, the attendance will be automatically set when the attendance record is created.
     */
    private function recordAttendanceForLeaveRequest(LeaveRequest $leaveRequest, $teacher, $timetable, $status)
    {
        try {
            $student = Student::where('user_id', $leaveRequest->student_id)->first();
            if (!$student) {
                Log::warning('Student not found for leave request', ['leave_request_id' => $leaveRequest->id]);
                return;
            }

            // Get all timetables for this teacher's subject in the student's class
            $timetables = Timetable::whereHas('classSubject', function($query) use ($teacher, $student) {
                    $query->where('teacher_id', $teacher->user_id)
                          ->where('class_id', $student->class_id);
                })
                ->where('class_subject_id', $timetable->classSubject->id)
                ->get();

            if ($timetables->isEmpty()) {
                Log::warning('No timetables found for leave request', [
                    'leave_request_id' => $leaveRequest->id,
                    'teacher_id' => $teacher->user_id,
                    'student_id' => $student->user_id
                ]);
                return;
            }

            // Get date range for leave request
            $startDate = Carbon::parse($leaveRequest->start_date);
            $endDate = Carbon::parse($leaveRequest->end_date);
            $today = Carbon::parse(TimeOverrideService::today());

            // Iterate through each day in the leave period
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                // Record attendance for past dates and today
                // For future dates, attendance will be automatically set when attendance record is created
                if ($currentDate->lte($today)) {
                    $dayOfWeek = $currentDate->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                    
                    // Convert to database format (1 = Monday, 7 = Sunday)
                    $dbDayOfWeek = $dayOfWeek == 0 ? 7 : $dayOfWeek;

                    // Find timetables for this day
                    $dayTimetables = $timetables->where('day_of_week', $dbDayOfWeek);

                    foreach ($dayTimetables as $dayTimetable) {
                        // Create or get ClassSession
                        $classSession = ClassSession::firstOrCreate(
                            [
                                'timetable_id' => $dayTimetable->id,
                                'date' => $currentDate->format('Y-m-d')
                            ],
                            [
                                'status' => 'completed',
                                'opened_by' => $teacher->user_id
                            ]
                        );

                        // Check if attendance record already exists
                        $existingAttendance = Attendance::where('class_session_id', $classSession->id)
                            ->where('student_id', $student->user_id)
                            ->first();

                        if (!$existingAttendance) {
                            // Create attendance record
                            Attendance::create([
                                'class_session_id' => $classSession->id,
                                'student_id' => $student->user_id,
                                'status' => $status,
                                'notes' => $status === 'I' 
                                    ? 'Izin (disetujui oleh guru)' 
                                    : 'Alpha (permohonan izin ditolak)',
                            ]);

                            Log::info('Attendance recorded for leave request', [
                                'leave_request_id' => $leaveRequest->id,
                                'student_id' => $student->user_id,
                                'timetable_id' => $dayTimetable->id,
                                'date' => $currentDate->format('Y-m-d'),
                                'status' => $status
                            ]);
                        } else {
                            // Update existing attendance if it's not already set
                            if ($existingAttendance->status !== $status) {
                                $existingAttendance->update([
                                    'status' => $status,
                                    'notes' => $status === 'I' 
                                        ? 'Izin (disetujui oleh guru)' 
                                        : 'Alpha (permohonan izin ditolak)',
                                ]);

                                Log::info('Attendance updated for leave request', [
                                    'leave_request_id' => $leaveRequest->id,
                                    'attendance_id' => $existingAttendance->id,
                                    'old_status' => $existingAttendance->getOriginal('status'),
                                    'new_status' => $status
                                ]);
                            }
                        }
                    }
                }

                $currentDate->addDay();
            }
        } catch (\Exception $e) {
            Log::error('Error recording attendance for leave request: ' . $e->getMessage(), [
                'leave_request_id' => $leaveRequest->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Check if there's a leave request that affects attendance for a specific date, student, and timetable
     * Returns 'I' for approved, 'A' for rejected, or null if no leave request found
     */
    public static function checkLeaveRequestForAttendance($studentId, $timetableId, $date)
    {
        try {
            $dateCarbon = Carbon::parse($date);
            $dayOfWeek = $dateCarbon->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $dbDayOfWeek = $dayOfWeek == 0 ? 7 : $dayOfWeek;

            // Get timetable with class subject
            $timetable = Timetable::with('classSubject')->find($timetableId);
            if (!$timetable || !$timetable->classSubject) {
                return null;
            }

            $teacherId = $timetable->classSubject->teacher_id;
            if (!$teacherId) {
                return null;
            }

            // Find leave requests for this student that cover this date
            $leaveRequests = LeaveRequest::where('student_id', $studentId)
                ->where('start_date', '<=', $dateCarbon->format('Y-m-d'))
                ->where('end_date', '>=', $dateCarbon->format('Y-m-d'))
                ->get();

            foreach ($leaveRequests as $leaveRequest) {
                // Check if this teacher has approved or rejected this leave request
                if ($leaveRequest->isApprovedByTeacher($teacherId)) {
                    // Check if this timetable matches the day of week
                    if ($timetable->day_of_week == $dbDayOfWeek) {
                        return 'I'; // Izin
                    }
                } elseif ($leaveRequest->isRejectedByTeacher($teacherId)) {
                    // Check if this timetable matches the day of week
                    if ($timetable->day_of_week == $dbDayOfWeek) {
                        return 'A'; // Alpha
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error checking leave request for attendance: ' . $e->getMessage(), [
                'student_id' => $studentId,
                'timetable_id' => $timetableId,
                'date' => $date,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * View supporting document (display in browser, not download)
     */
    public function viewDocument($id)
    {
        try {
            $leaveRequest = LeaveRequest::findOrFail($id);
            
            if (!$leaveRequest->supporting_document) {
                abort(404, 'Dokumen pendukung tidak ditemukan.');
            }

            // Get file path
            $filePath = storage_path('app/public/' . $leaveRequest->supporting_document);
            
            if (!file_exists($filePath)) {
                abort(404, 'File tidak ditemukan.');
            }

            // Get file extension and determine MIME type
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            
            $mimeType = $mimeTypes[$extension] ?? mime_content_type($filePath) ?: 'application/octet-stream';
            
            // Get filename for Content-Disposition
            $filename = basename($filePath);
            
            // Set headers to display in browser (not download)
            // Content-Disposition: inline tells browser to display, not download
            return Response::file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'public, max-age=3600',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error viewing document: ' . $e->getMessage(), [
                'leave_request_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'Gagal menampilkan dokumen.');
        }
    }
}
