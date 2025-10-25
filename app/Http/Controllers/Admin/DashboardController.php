<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Attendance;
use App\Models\Timetable;
use App\Models\LeaveRequest;
use App\Models\ClassSubject;
use App\Services\TimeOverrideService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Always set default values first
        $totalTeachers = 0;
        $totalStudents = 0;
        $totalSubjects = 0;
        $totalClasses = 0;
        $todayAttendance = 0;
        $todayLeaveRequests = 0;
        $todayActiveSessions = 0;
        $teacherPerformance = collect();
        $attendanceTrends = [];
        $classStatistics = collect();
        $recentActivities = collect();
        $teacherWorkload = collect();
        $teacherPagination = [];
        
        try {
            $today = TimeOverrideService::now();
            
            // Basic statistics
            $totalTeachers = Teacher::count();
            $totalStudents = Student::count();
            $totalSubjects = Subject::count();
            $totalClasses = Classroom::count();
            
            // Today's statistics
            $todayAttendance = Attendance::whereDate('created_at', $today->toDateString())->count();
            $todayLeaveRequests = LeaveRequest::whereDate('created_at', $today->toDateString())->count();
            
            // Check if attendance_sessions table exists
            try {
                $todayActiveSessions = DB::table('attendance_sessions')
                    ->whereDate('created_at', $today->toDateString())
                    ->where('is_active', true)
                    ->count();
            } catch (\Exception $e) {
                // Table might not exist, set to 0
                $todayActiveSessions = 0;
            }
            
            // Teacher performance data (last 7 days)
            $teacherPerformance = $this->getTeacherPerformanceData($today);
            
            // Student attendance trends (last 30 days)
            $attendanceTrends = $this->getAttendanceTrends($today);
            
            // Class statistics with pagination
            $classPage = $request->get('class_page', 1);
            $classStatistics = $this->getClassStatisticsWithPagination($classPage, 10);
            
            // Get paginated recent activities
            $activitiesPage = $request->get('activities_page', 1);
            $recentActivities = $this->getRecentActivitiesWithPagination($activitiesPage, 5);
            
            
            // Teacher workload data
            $teacherWorkload = $this->getTeacherWorkloadData();
            
            // Get paginated active teachers (worst performers)
            $page = $request->get('page', 1);
            $teacherPagination = $this->getAllActiveTeachersWithPagination($today, $page, 5);
        } catch (\Exception $e) {
            // Keep default values if error occurs
            Log::error('Admin Dashboard Error: ' . $e->getMessage());
        }
        
        return view('admin.dashboard', compact(
            'totalTeachers',
            'totalStudents', 
            'totalSubjects',
            'totalClasses',
            'todayAttendance',
            'todayLeaveRequests',
            'todayActiveSessions',
            'teacherPerformance',
            'attendanceTrends',
            'classStatistics',
            'recentActivities',
            'teacherWorkload',
            'teacherPagination'
        ));
    }
    
    public function getTeacherPagination(Request $request)
    {
        try {
            $today = TimeOverrideService::now();
            $page = $request->get('page', 1);
            $perPage = 5;

            $teacherPagination = $this->getAllActiveTeachersWithPagination($today, $page, $perPage);

            return response()->json([
                'success' => true,
                'data' => $teacherPagination
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getClassPagination(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = 10;

            $classPagination = $this->getClassStatisticsWithPagination($page, $perPage);

            return response()->json([
                'success' => true,
                'data' => $classPagination
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getActivitiesPagination(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = 5; // 5 activities per page

            $activitiesPagination = $this->getRecentActivitiesWithPagination($page, $perPage);

            return response()->json([
                'success' => true,
                'data' => $activitiesPagination
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getTeacherPerformanceData($today)
    {
        try {
            $last7Days = $today->copy()->subDays(6);
            
            $performance = Teacher::with(['user', 'classes'])
                ->whereHas('user', function($query) {
                    $query->where('status', 'active'); // Only active teachers
                })
                ->get()
                ->map(function ($teacher) use ($last7Days, $today) {
                // Get scheduled teaching hours for last 7 days (from timetables)
                // Use day_of_week to get timetables for the current week
                $currentWeekDays = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = $today->copy()->subDays($i);
                    $currentWeekDays[] = $date->dayOfWeek; // 0=Sunday, 1=Monday, etc.
                }
                
                $scheduledHours = Timetable::whereHas('classSubject.teacher', function($query) use ($teacher) {
                    $query->where('user_id', $teacher->user_id);
                })
                ->whereIn('day_of_week', $currentWeekDays)
                ->get()
                ->sum(function ($timetable) {
                    return Carbon::parse($timetable->start_time)->diffInMinutes(Carbon::parse($timetable->end_time));
                });
                
                // Get actual teaching hours based on attendance sessions conducted
                $actualHours = $this->getActualTeachingHours($teacher->user_id, $last7Days, $today);
                
                // Get attendance sessions conducted
                $sessionsConducted = DB::table('attendance_sessions')
                    ->where('teacher_id', $teacher->user_id)
                    ->whereBetween('created_at', [$last7Days, $today])
                    ->where('is_active', false) // Only completed sessions
                    ->count();
                
                // Get student attendance rate for this teacher's classes
                $attendanceRate = $this->getTeacherAttendanceRate($teacher->user_id, $last7Days, $today);
                
                // Calculate compliance rate (actual vs scheduled)
                $complianceRate = $scheduledHours > 0 ? round(($actualHours / $scheduledHours) * 100, 1) : 0;
                
                return [
                    'name' => $teacher->user->full_name ?? 'N/A',
                    'nip' => $teacher->nip ?? 'N/A',
                    'scheduled_hours' => round($scheduledHours / 60, 1),
                    'actual_hours' => round($actualHours / 60, 1),
                    'sessions_conducted' => $sessionsConducted,
                    'attendance_rate' => $attendanceRate,
                    'compliance_rate' => $complianceRate,
                    'classes_count' => $teacher->classes->count()
                ];
            })
            ->sortBy('compliance_rate') // Sort by compliance rate (lowest first - worst performers)
            ->sortBy('actual_hours')    // Secondary sort by actual hours (lowest first)
            ->take(15); // Show 15 worst performers
            
            return $performance;
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    private function getActualTeachingHours($teacherId, $startDate, $endDate)
    {
        try {
            // Get completed attendance sessions for this teacher
            $sessions = DB::table('attendance_sessions')
                ->join('timetables', 'attendance_sessions.timetable_id', '=', 'timetables.id')
                ->where('attendance_sessions.teacher_id', $teacherId)
                ->whereBetween('attendance_sessions.created_at', [$startDate, $endDate])
                ->where('attendance_sessions.is_active', false) // Only completed sessions
                ->select('timetables.start_time', 'timetables.end_time')
                ->get();
            
            // Calculate total minutes from completed sessions
            $totalMinutes = $sessions->sum(function ($session) {
                return Carbon::parse($session->start_time)->diffInMinutes(Carbon::parse($session->end_time));
            });
            
            return $totalMinutes;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getTeacherAttendanceRate($teacherId, $startDate, $endDate)
    {
        $totalStudents = Student::whereHas('classroom.classSubjects.teacher', function($query) use ($teacherId) {
            $query->where('user_id', $teacherId);
        })->count();
        
        if ($totalStudents == 0) return 0;
        
        $attendedStudents = Attendance::whereHas('classSession.timetable.classSubject.teacher', function($query) use ($teacherId) {
            $query->where('user_id', $teacherId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('status', ['H', 'T']) // Hadir or Terlambat
        ->distinct('student_id')
        ->count();
        
        return round(($attendedStudents / $totalStudents) * 100, 1);
    }
    
    private function getAttendanceTrends($today)
    {
        try {
            $last30Days = $today->copy()->subDays(29);
            
            $trends = [];
            for ($i = 29; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            
            $totalAttendance = Attendance::whereDate('created_at', $date->toDateString())->count();
            $presentCount = Attendance::whereDate('created_at', $date->toDateString())
                ->whereIn('status', ['H', 'T'])
                ->count();
            $absentCount = Attendance::whereDate('created_at', $date->toDateString())
                ->whereIn('status', ['A', 'S', 'I'])
                ->count();
            
            $trends[] = [
                'date' => $date->format('M d'),
                'total' => $totalAttendance,
                'present' => $presentCount,
                'absent' => $absentCount,
                'rate' => $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0
            ];
        }
        
        return $trends;
        } catch (\Exception $e) {
            return [];
        }
    }
    
    
    private function getClassStatistics()
    {
        try {
            return Classroom::with(['homeroomTeacher.user', 'students'])
                ->get()
                ->map(function ($class) {
                    // Determine group for Grade 11 classes
                    $group = '-';
                    if ($class->grade == 11) {
                        // Define groups based on class names
                        $groupA = ['TKJA', 'TKJC', 'RPLA', 'RPLC', 'KTA', 'DKVA', 'PSPTA'];
                        $groupB = ['TKJB', 'RPLB', 'KK', 'KTB', 'DKVB', 'PSPTB'];
                        
                        if (in_array($class->name, $groupA)) {
                            $group = 'Kelompok A';
                        } elseif (in_array($class->name, $groupB)) {
                            $group = 'Kelompok B';
                        }
                    }
                    
                    return [
                        'name' => $class->name,
                        'grade' => $class->grade,
                        'group' => $group,
                        'homeroom_teacher' => $class->homeroomTeacher->user->full_name ?? 'N/A',
                        'students_count' => $class->students->count(),
                        'subjects_count' => ClassSubject::where('class_id', $class->id)->count()
                    ];
                })
                ->sortBy(function ($class) {
                    // Create a sort key: grade + group + name
                    $grade = $class['grade'];
                    $group = $class['group'] === '-' ? '0' : ($class['group'] === 'Kelompok A' ? '1' : '2');
                    $name = $class['name'];
                    return $grade . $group . $name;
                })
                ->values(); // Reset array keys to get sequential index
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    private function getClassStatisticsWithPagination($page = 1, $perPage = 10)
    {
        try {
            $allClasses = Classroom::with(['homeroomTeacher.user', 'students'])
                ->get()
                ->map(function ($class) {
                    // Determine group for Grade 11 classes
                    $group = '-';
                    if ($class->grade == 11) {
                        // Define groups based on class names
                        $groupA = ['TKJA', 'TKJC', 'RPLA', 'RPLC', 'KTA', 'DKVA', 'PSPTA'];
                        $groupB = ['TKJB', 'RPLB', 'KK', 'KTB', 'DKVB', 'PSPTB'];
                        
                        if (in_array($class->name, $groupA)) {
                            $group = 'Kelompok A';
                        } elseif (in_array($class->name, $groupB)) {
                            $group = 'Kelompok B';
                        }
                    }
                    
                    return [
                        'name' => $class->name,
                        'grade' => $class->grade,
                        'group' => $group,
                        'homeroom_teacher' => $class->homeroomTeacher->user->full_name ?? 'N/A',
                        'students_count' => $class->students->count(),
                        'subjects_count' => ClassSubject::where('class_id', $class->id)->count()
                    ];
                })
                ->sortBy(function ($class) {
                    // Create a sort key: grade + group + name
                    $grade = $class['grade'];
                    $group = $class['group'] === '-' ? '0' : ($class['group'] === 'Kelompok A' ? '1' : '2');
                    $name = $class['name'];
                    return $grade . $group . $name;
                })
                ->values(); // Reset array keys to get sequential index
            
            // Manual pagination
            $total = $allClasses->count();
            $offset = ($page - 1) * $perPage;
            $items = $allClasses->slice($offset, $perPage)->values();
            
            return [
                'data' => $items,
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ];
        } catch (\Exception $e) {
            return [
                'data' => collect(),
                'current_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'last_page' => 1,
                'from' => 0,
                'to' => 0
            ];
        }
    }
    
    private function getRecentActivities()
    {
        try {
            $activities = collect();
        
        // Recent attendance sessions
        $recentSessions = DB::table('attendance_sessions')
            ->join('users', 'attendance_sessions.teacher_id', '=', 'users.id')
            ->join('timetables', 'attendance_sessions.timetable_id', '=', 'timetables.id')
            ->join('class_subjects', 'timetables.class_subject_id', '=', 'class_subjects.id')
            ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
            ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
            ->select(
                'attendance_sessions.created_at',
                'users.full_name as teacher_name',
                'subjects.name as subject_name',
                'classes.name as class_name',
                'attendance_sessions.is_active'
            )
            ->orderBy('attendance_sessions.created_at', 'desc')
            ->limit(10)
            ->get();
            
        foreach ($recentSessions as $session) {
            $activities->push([
                'type' => 'attendance_session',
                'time' => Carbon::parse($session->created_at),
                'description' => $session->is_active 
                    ? "{$session->teacher_name} memulai sesi absensi untuk {$session->subject_name} - {$session->class_name}"
                    : "{$session->teacher_name} mengakhiri sesi absensi untuk {$session->subject_name} - {$session->class_name}",
                'icon' => $session->is_active ? 'play-circle' : 'stop-circle',
                'color' => $session->is_active ? 'success' : 'warning'
            ]);
        }
        
        // Recent leave requests
        $recentLeaveRequests = LeaveRequest::with(['student'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($recentLeaveRequests as $request) {
            $activities->push([
                'type' => 'leave_request',
                'time' => $request->created_at,
                'description' => "{$request->student->full_name} mengajukan permohonan izin ({$request->leave_type_display})",
                'icon' => 'file-text',
                'color' => $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning')
            ]);
        }
        
        return $activities->sortByDesc('time')->take(15);
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    private function getRecentActivitiesWithPagination($page = 1, $perPage = 5)
    {
        try {
            $activities = collect();
        
        // Recent attendance sessions
        $recentSessions = DB::table('attendance_sessions')
            ->join('users', 'attendance_sessions.teacher_id', '=', 'users.id')
            ->join('timetables', 'attendance_sessions.timetable_id', '=', 'timetables.id')
            ->join('class_subjects', 'timetables.class_subject_id', '=', 'class_subjects.id')
            ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
            ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
            ->select(
                'attendance_sessions.created_at',
                'users.full_name as teacher_name',
                'subjects.name as subject_name',
                'classes.name as class_name',
                'attendance_sessions.is_active'
            )
            ->orderBy('attendance_sessions.created_at', 'desc')
            ->limit(20) // Get more data for pagination
            ->get();
            
        foreach ($recentSessions as $session) {
            $time = Carbon::parse($session->created_at);
            $activities->push([
                'type' => 'attendance_session',
                'time' => $time,
                'time_formatted' => $time->diffForHumans(),
                'description' => $session->is_active 
                    ? "{$session->teacher_name} memulai sesi absensi untuk {$session->subject_name} - {$session->class_name}"
                    : "{$session->teacher_name} mengakhiri sesi absensi untuk {$session->subject_name} - {$session->class_name}",
                'icon' => $session->is_active ? 'play-circle' : 'stop-circle',
                'color' => $session->is_active ? 'success' : 'warning'
            ]);
        }
        
        // Recent leave requests
        $recentLeaveRequests = LeaveRequest::with(['student'])
            ->orderBy('created_at', 'desc')
            ->limit(10) // Get more data for pagination
            ->get();
            
        foreach ($recentLeaveRequests as $request) {
            $time = Carbon::parse($request->created_at);
            $activities->push([
                'type' => 'leave_request',
                'time' => $time,
                'time_formatted' => $time->diffForHumans(),
                'description' => "{$request->student->full_name} mengajukan permohonan izin ({$request->leave_type_display})",
                'icon' => 'file-text',
                'color' => $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning')
            ]);
        }
        
        // Sort all activities by time and apply pagination
        $allActivities = $activities->sortByDesc('time');
        
        // Manual pagination
        $total = $allActivities->count();
        $offset = ($page - 1) * $perPage;
        $items = $allActivities->slice($offset, $perPage)->values();
        
        return [
            'data' => $items,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
        } catch (\Exception $e) {
            return [
                'data' => collect(),
                'current_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'last_page' => 1,
                'from' => 0,
                'to' => 0
            ];
        }
    }
    
    
    
    private function getTeacherWorkloadData()
    {
        try {
            return Teacher::with(['user', 'classes'])
                ->get()
                ->map(function ($teacher) {
                    $totalHours = Timetable::whereHas('classSubject.teacher', function($query) use ($teacher) {
                        $query->where('user_id', $teacher->user_id);
                    })
                    ->get()
                    ->sum(function ($timetable) {
                        return Carbon::parse($timetable->start_time)->diffInMinutes(Carbon::parse($timetable->end_time));
                    });
                    
                    return [
                        'name' => $teacher->user->full_name ?? 'N/A',
                        'nip' => $teacher->nip ?? 'N/A',
                        'total_hours' => round($totalHours / 60, 1),
                        'classes_count' => $teacher->classes->count(),
                        'subjects_count' => ClassSubject::where('teacher_id', $teacher->user_id)->count()
                    ];
                })
                ->sortByDesc('total_hours');
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    private function getAllActiveTeachersWithPagination($today, $page = 1, $perPage = 5)
    {
        try {
            $last7Days = $today->copy()->subDays(6);
            
            $allTeachers = Teacher::with(['user', 'classes'])
                ->whereHas('user', function($query) {
                    $query->where('status', 'active');
                })
                ->get()
                ->map(function ($teacher) use ($last7Days, $today) {
                    // Get scheduled teaching hours for last 7 days (from timetables)
                    // Use day_of_week to get timetables for the current week
                    $currentWeekDays = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = $today->copy()->subDays($i);
                        $currentWeekDays[] = $date->dayOfWeek; // 0=Sunday, 1=Monday, etc.
                    }
                    
                    $scheduledHours = Timetable::whereHas('classSubject.teacher', function($query) use ($teacher) {
                        $query->where('user_id', $teacher->user_id);
                    })
                    ->whereIn('day_of_week', $currentWeekDays)
                    ->get()
                    ->sum(function ($timetable) {
                        return Carbon::parse($timetable->start_time)->diffInMinutes(Carbon::parse($timetable->end_time));
                    });
                    
                    // Get actual teaching hours based on attendance sessions conducted
                    $actualHours = $this->getActualTeachingHours($teacher->user_id, $last7Days, $today);
                    
                    // Get attendance sessions conducted
                    $sessionsConducted = DB::table('attendance_sessions')
                        ->where('teacher_id', $teacher->user_id)
                        ->whereBetween('created_at', [$last7Days, $today])
                        ->where('is_active', false)
                        ->count();
                    
                    // Get student attendance rate for this teacher's classes
                    $attendanceRate = $this->getTeacherAttendanceRate($teacher->user_id, $last7Days, $today);
                    
                    // Calculate compliance rate (actual vs scheduled)
                    $complianceRate = $scheduledHours > 0 ? round(($actualHours / $scheduledHours) * 100, 1) : 0;
                    
                    return [
                        'name' => $teacher->user->full_name ?? 'N/A',
                        'nip' => $teacher->nip ?? 'N/A',
                        'scheduled_hours' => round($scheduledHours / 60, 1),
                        'actual_hours' => round($actualHours / 60, 1),
                        'sessions_conducted' => $sessionsConducted,
                        'attendance_rate' => $attendanceRate,
                        'compliance_rate' => $complianceRate,
                        'classes_count' => $teacher->classes->count()
                    ];
                })
                ->sortBy('compliance_rate') // Sort by compliance rate (lowest first - worst performers)
                ->sortBy('actual_hours')    // Secondary sort by actual hours (lowest first)
                ->values(); // Reset array keys
            
            // Manual pagination
            $total = $allTeachers->count();
            $offset = ($page - 1) * $perPage;
            $items = $allTeachers->slice($offset, $perPage)->values();
            
            return [
                'data' => $items,
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ];
        } catch (\Exception $e) {
            return [
                'data' => collect(),
                'current_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'last_page' => 1,
                'from' => 0,
                'to' => 0
            ];
        }
    }
}
