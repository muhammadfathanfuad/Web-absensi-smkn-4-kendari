<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\ClassSession;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use App\Models\StudentPresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\TimeOverrideService;
use App\Services\AttendanceCacheService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        // Initialize variables first to ensure they're always defined
        $classes = collect();
        $subjects = collect();
        $teachers = collect();
        $dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = Carbon::now()->format('Y-m-d');
        $classId = null;
        $subjectId = null;
        $teacherId = null;
        $reportType = 'overview';
        
        try {
            // Get filter parameters
            $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
            $classId = $request->get('class_id');
            $subjectId = $request->get('subject_id');
            $teacherId = $request->get('teacher_id');
            $reportType = $request->get('report_type', 'overview');

            // Get basic data for filters
            try {
                $classes = Classroom::with('students')->get();
                $subjects = Subject::all();
                $teachers = Teacher::with('user')->get();
            } catch (\Exception $e) {
                Log::error('Error loading filter data: ' . $e->getMessage());
                // Keep default empty collections
            }

            // Initialize data with default values
            $data = [
                'summary' => [
                    'total_records' => 0,
                    'present_count' => 0,
                    'late_count' => 0,
                    'absent_count' => 0,
                    'present_percentage' => 0,
                    'late_percentage' => 0,
                    'absent_percentage' => 0,
                ],
                'daily_stats' => collect(),
                'class_stats' => collect(),
                'subject_stats' => collect(),
                'low_attendance_students' => collect(),
            ];

            // Get statistics based on report type
            try {
                switch ($reportType) {
                    case 'overview':
                        $data = $this->getOverviewStats($dateFrom, $dateTo, $classId, $subjectId, $teacherId);
                        break;
                    case 'class':
                        $data = $this->getClassReport($dateFrom, $dateTo, $classId);
                        break;
                    case 'student':
                        $data = $this->getStudentReport($dateFrom, $dateTo, $classId);
                        break;
                    case 'subject':
                        $data = $this->getSubjectReport($dateFrom, $dateTo, $subjectId);
                        break;
                    case 'teacher':
                        $data = $this->getTeacherReport($dateFrom, $dateTo, $teacherId);
                        break;
                }
            } catch (\Exception $e) {
                // Log error and use default data
                Log::error('Error getting report data: ' . $e->getMessage());
            }

            return view('admin.laporan', compact(
                'data',
                'classes',
                'subjects',
                'teachers',
                'dateFrom',
                'dateTo',
                'classId',
                'subjectId',
                'teacherId',
                'reportType'
            ));
        } catch (\Exception $e) {
            Log::error('Error in AdminReportController index: ' . $e->getMessage());

            // Return view with default values
            $dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
            $dateTo = Carbon::now()->format('Y-m-d');
            $classId = null;
            $subjectId = null;
            $teacherId = null;
            $reportType = 'overview';

            $classes = collect();
            $subjects = collect();
            $teachers = collect();

            $data = [
                'summary' => [
                    'total_records' => 0,
                    'present_count' => 0,
                    'late_count' => 0,
                    'absent_count' => 0,
                    'present_percentage' => 0,
                    'late_percentage' => 0,
                    'absent_percentage' => 0,
                ],
                'daily_stats' => collect(),
                'class_stats' => collect(),
                'subject_stats' => collect(),
                'low_attendance_students' => collect(),
            ];

            return view('admin.laporan', compact(
                'data',
                'classes',
                'subjects',
                'teachers',
                'dateFrom',
                'dateTo',
                'classId',
                'subjectId',
                'teacherId',
                'reportType'
            ));
        }
    }

    private function getOverviewStats($dateFrom, $dateTo, $classId = null, $subjectId = null, $teacherId = null)
    {
        try {
            // Simple query first to test
            $totalRecords = Attendance::count();
            $presentCount = Attendance::where('status', 'H')->count();
            $lateCount = Attendance::where('status', 'T')->count();
            $absentCount = Attendance::where('status', 'A')->count();

            // Calculate percentages
            $presentPercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;
            $latePercentage = $totalRecords > 0 ? round(($lateCount / $totalRecords) * 100, 2) : 0;
            $absentPercentage = $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 2) : 0;

            return [
                'summary' => [
                    'total_records' => $totalRecords,
                    'present_count' => $presentCount,
                    'late_count' => $lateCount,
                    'absent_count' => $absentCount,
                    'present_percentage' => $presentPercentage,
                    'late_percentage' => $latePercentage,
                    'absent_percentage' => $absentPercentage,
                ],
                'daily_stats' => collect(),
                'class_stats' => collect(),
                'subject_stats' => collect(),
                'low_attendance_students' => collect(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getOverviewStats: ' . $e->getMessage());
            return [
                'summary' => [
                    'total_records' => 0,
                    'present_count' => 0,
                    'late_count' => 0,
                    'absent_count' => 0,
                    'present_percentage' => 0,
                    'late_percentage' => 0,
                    'absent_percentage' => 0,
                ],
                'daily_stats' => collect(),
                'class_stats' => collect(),
                'subject_stats' => collect(),
                'low_attendance_students' => collect(),
            ];
        }
    }

    private function getClassReport($dateFrom, $dateTo, $classId = null)
    {
        try {
            return [
                'class_summary' => collect(),
                'student_details' => collect(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getClassReport: ' . $e->getMessage());
            return [
                'class_summary' => collect(),
                'student_details' => collect(),
            ];
        }
    }

    private function getStudentReport($dateFrom, $dateTo, $classId = null)
    {
        try {
            return [
                'student_summary' => collect(),
                'subject_details' => collect(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getStudentReport: ' . $e->getMessage());
            return [
                'student_summary' => collect(),
                'subject_details' => collect(),
            ];
        }
    }

    private function getSubjectReport($dateFrom, $dateTo, $subjectId = null)
    {
        try {
            return [
                'subject_summary' => collect(),
                'class_details' => collect(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getSubjectReport: ' . $e->getMessage());
            return [
                'subject_summary' => collect(),
                'class_details' => collect(),
            ];
        }
    }

    private function getTeacherReport($dateFrom, $dateTo, $teacherId = null)
    {
        try {
            return [
                'teacher_summary' => collect(),
                'subject_class_details' => collect(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getTeacherReport: ' . $e->getMessage());
            return [
                'teacher_summary' => collect(),
                'subject_class_details' => collect(),
            ];
        }
    }

    public function export(Request $request)
    {
        try {
            Log::info('Export request received', $request->all());

            // Validate format parameter
            $format = $request->get('format', 'pdf');
            if (!in_array($format, ['pdf'])) {
                $format = 'pdf';
            }

            // Get filter parameters
            $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
            $classId = $request->get('class_id');
            $subjectId = $request->get('subject_id');
            $teacherId = $request->get('teacher_id');
            $reportType = $request->get('report_type', 'overview');

            // Validate report type
            $validReportTypes = ['overview', 'class', 'student', 'subject', 'teacher'];
            if (!in_array($reportType, $validReportTypes)) {
                $reportType = 'overview';
            }

            Log::info('Export parameters', [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'reportType' => $reportType,
                'format' => $format
            ]);

            // Report type labels for filename
            $reportLabels = [
                'overview' => 'ringkasan',
                'class' => 'per_kelas',
                'student' => 'per_siswa',
                'subject' => 'per_mata_pelajaran',
                'teacher' => 'per_guru'
            ];

            // Generate filename with better formatting
            $reportLabel = $reportLabels[$reportType] ?? 'laporan';
            $filename = 'laporan_kehadiran_' . $reportLabel . '_' . date('Ymd', strtotime($dateFrom)) . '_' . date('Ymd', strtotime($dateTo)) . '.' . $format;

            // Get data based on report type for PDF
            $data = $this->getExportDataForPDF($reportType, $dateFrom, $dateTo, $classId, $subjectId, $teacherId);

            Log::info('Export data prepared for PDF', ['report_type' => $reportType]);

            // Generate PDF using dompdf
            $pdf = Pdf::loadView('admin.laporan-pdf', [
                'data' => $data,
                'reportType' => $reportType,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'reportLabels' => [
                    'class' => 'Laporan Per Kelas',
                    'student' => 'Laporan Per Siswa',
                    'teacher' => 'Laporan Per Guru',
                ]
            ]);

            Log::info('Returning PDF file', ['filename' => $filename]);
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Return JSON error for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengexport data: ' . $e->getMessage()
                ], 500);
            }

            // Redirect back with error message for regular requests
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    private function getSimpleExportData($reportType, $dateFrom, $dateTo, $classId = null, $subjectId = null, $teacherId = null)
    {
        switch ($reportType) {
            case 'overview':
                return $this->getSimpleOverviewData();
            case 'class':
                return $this->getSimpleClassData();
            case 'student':
                return $this->getSimpleStudentData($classId);
            case 'subject':
                return $this->getSimpleSubjectData();
            case 'teacher':
                return $this->getSimpleTeacherData();
            default:
                return ['summary' => ['total_records' => 0, 'present_count' => 0, 'late_count' => 0, 'absent_count' => 0, 'present_percentage' => 0]];
        }
    }

    private function getSimpleOverviewData()
    {
        // Optimasi: Gunakan single query dengan agregasi untuk menghitung semua status sekaligus
        $stats = Attendance::selectRaw('
            COUNT(*) as total_records,
            SUM(CASE WHEN status = "H" THEN 1 ELSE 0 END) as present_count,
            SUM(CASE WHEN status = "T" THEN 1 ELSE 0 END) as late_count,
            SUM(CASE WHEN status = "A" THEN 1 ELSE 0 END) as absent_count
        ')->first();

        $totalRecords = $stats->total_records ?? 0;
        $presentCount = $stats->present_count ?? 0;
        $lateCount = $stats->late_count ?? 0;
        $absentCount = $stats->absent_count ?? 0;
        $presentPercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

        return [
            'summary' => [
                'total_records' => $totalRecords,
                'present_count' => $presentCount,
                'late_count' => $lateCount,
                'absent_count' => $absentCount,
                'present_percentage' => $presentPercentage,
            ]
        ];
    }

    private function getSimpleClassData()
    {
        // Optimasi: Gunakan cache untuk data kelas yang jarang berubah
        $classes = \App\Services\AttendanceCacheService::getClassrooms();
        $classSummary = collect();

        // Optimasi: Gunakan single query dengan join untuk menghitung semua status sekaligus per kelas
        foreach ($classes as $class) {
            // Query optimasi: Single query dengan agregasi
            $stats = Attendance::selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN attendances.status = "H" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendances.status = "T" THEN 1 ELSE 0 END) as late_count,
                SUM(CASE WHEN attendances.status = "A" THEN 1 ELSE 0 END) as absent_count
            ')
            ->join('class_sessions', 'attendances.class_session_id', '=', 'class_sessions.id')
            ->join('timetables', 'class_sessions.timetable_id', '=', 'timetables.id')
            ->join('class_subjects', 'timetables.class_subject_id', '=', 'class_subjects.id')
            ->where('class_subjects.class_id', $class->id)
            ->first();

            $totalRecords = $stats->total_records ?? 0;
            $presentCount = $stats->present_count ?? 0;
            $lateCount = $stats->late_count ?? 0;
            $absentCount = $stats->absent_count ?? 0;
            $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

            // Get student count (cached)
            $totalStudents = Student::where('class_id', $class->id)->count();

            $classSummary->push((object)[
                'grade' => $class->grade,
                'class_name' => $class->name,
                'total_students' => $totalStudents,
                'total_records' => $totalRecords,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $absentCount,
                'attendance_percentage' => $attendancePercentage
            ]);
        }

        return ['class_summary' => $classSummary];
    }

    private function getSimpleStudentData($classId = null)
    {
        // Optimasi: Select kolom spesifik dan gunakan chunking untuk data besar
        $studentsQuery = Student::with([
            'user:id,full_name',
            'classroom:id,grade,name'
        ])->select('user_id', 'nis', 'class_id');
        
        // Apply class filter if provided
        if ($classId) {
            $studentsQuery->where('class_id', $classId);
        }
        
        $studentSummary = collect();

        // Optimasi: Process dengan chunking untuk data besar
        $studentsQuery->chunk(200, function ($students) use (&$studentSummary) {
            foreach ($students as $student) {
                // Optimasi: Single query dengan agregasi untuk menghitung semua status sekaligus
                $stats = Attendance::selectRaw('
                    COUNT(*) as total_records,
                    SUM(CASE WHEN status = "H" THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = "T" THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN status = "A" THEN 1 ELSE 0 END) as absent_count
                ')
                ->where('student_id', $student->user_id)
                ->first();

                $totalRecords = $stats->total_records ?? 0;
                $presentCount = $stats->present_count ?? 0;
                $lateCount = $stats->late_count ?? 0;
                $absentCount = $stats->absent_count ?? 0;
                $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

                $studentSummary->push((object)[
                    'full_name' => $student->user->full_name ?? 'N/A',
                    'nis' => $student->nis ?? 'N/A',
                    'grade' => $student->classroom->grade ?? 'N/A',
                    'class_name' => $student->classroom->name ?? 'N/A',
                    'total_records' => $totalRecords,
                    'present' => $presentCount,
                    'late' => $lateCount,
                    'absent' => $absentCount,
                    'attendance_percentage' => $attendancePercentage
                ]);
            }
        });

        return ['student_summary' => $studentSummary];
    }

    private function getSimpleSubjectData()
    {
        // Optimasi: Gunakan cache untuk data mata pelajaran
        $subjects = \App\Services\AttendanceCacheService::getSubjects();
        $subjectSummary = collect();

        foreach ($subjects as $subject) {
            // Optimasi: Single query dengan join dan agregasi
            $stats = Attendance::selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN attendances.status = "H" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendances.status = "T" THEN 1 ELSE 0 END) as late_count,
                SUM(CASE WHEN attendances.status = "A" THEN 1 ELSE 0 END) as absent_count
            ')
            ->join('class_sessions', 'attendances.class_session_id', '=', 'class_sessions.id')
            ->join('timetables', 'class_sessions.timetable_id', '=', 'timetables.id')
            ->join('class_subjects', 'timetables.class_subject_id', '=', 'class_subjects.id')
            ->where('class_subjects.subject_id', $subject->id)
            ->first();

            $totalRecords = $stats->total_records ?? 0;
            $presentCount = $stats->present_count ?? 0;
            $lateCount = $stats->late_count ?? 0;
            $absentCount = $stats->absent_count ?? 0;
            $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

            $subjectSummary->push((object)[
                'subject_name' => $subject->name,
                'subject_code' => $subject->code ?? 'N/A',
                'total_students' => 0, // Simplified for now
                'total_records' => $totalRecords,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $absentCount,
                'attendance_percentage' => $attendancePercentage
            ]);
        }

        return ['subject_summary' => $subjectSummary];
    }

    private function getSimpleTeacherData()
    {
        $teachers = Teacher::with('user')->get();
        $teacherSummary = collect();

        foreach ($teachers as $teacher) {
            $totalRecords = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })->count();

            $presentCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })->where('status', 'H')->count();

            $lateCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })->where('status', 'T')->count();

            $absentCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })->where('status', 'A')->count();

            $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

            $teacherSummary->push((object)[
                'teacher_name' => $teacher->user->full_name,
                'nip' => $teacher->nip,
                'total_subjects' => 0, // Simplified for now
                'total_classes' => 0, // Simplified for now
                'total_records' => $totalRecords,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $absentCount,
                'attendance_percentage' => $attendancePercentage
            ]);
        }

        return ['teacher_summary' => $teacherSummary];
    }

    private function getExportDataForPDF($reportType, $dateFrom, $dateTo, $classId = null, $subjectId = null, $teacherId = null)
    {
        $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
        $dateToCarbon = Carbon::parse($dateTo)->endOfDay();

        switch ($reportType) {
            case 'class':
                return $this->getClassDataForPDF($dateFromCarbon, $dateToCarbon);
            case 'student':
                return $this->getStudentDataForPDF($dateFromCarbon, $dateToCarbon, $classId);
            case 'teacher':
                return $this->getTeacherDataForPDF($dateFromCarbon, $dateToCarbon);
            default:
                return [];
        }
    }

    private function getClassDataForPDF($dateFromCarbon, $dateToCarbon)
    {
        // Optimasi: Gunakan cache untuk data kelas
        $classes = AttendanceCacheService::getClassrooms();
        $classData = [];

        foreach ($classes as $class) {
            // Optimasi: Single query dengan agregasi dan filter tanggal
            $stats = Attendance::selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN attendances.status = "H" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendances.status = "T" THEN 1 ELSE 0 END) as late_count,
                SUM(CASE WHEN attendances.status = "A" THEN 1 ELSE 0 END) as absent_count
            ')
            ->join('class_sessions', 'attendances.class_session_id', '=', 'class_sessions.id')
            ->join('timetables', 'class_sessions.timetable_id', '=', 'timetables.id')
            ->join('class_subjects', 'timetables.class_subject_id', '=', 'class_subjects.id')
            ->where('class_subjects.class_id', $class->id)
            ->whereBetween('attendances.created_at', [$dateFromCarbon, $dateToCarbon])
            ->first();

            $classTotal = $stats->total_records ?? 0;
            $classPresent = $stats->present_count ?? 0;
            $classLate = $stats->late_count ?? 0;
            $classAbsent = $stats->absent_count ?? 0;
            $classPercentage = $classTotal > 0 ? round(($classPresent / $classTotal) * 100, 2) : 0;

            // Get student count
            $totalStudents = Student::where('class_id', $class->id)->count();

            $classData[] = [
                'grade' => $class->grade,
                'name' => $class->name,
                'total_students' => $totalStudents,
                'total_records' => $classTotal,
                'present' => $classPresent,
                'late' => $classLate,
                'absent' => $classAbsent,
                'percentage' => $classPercentage,
            ];
        }

        return $classData;
    }

    private function getStudentDataForPDF($dateFromCarbon, $dateToCarbon, $classId = null)
    {
        // Optimasi: Select kolom spesifik dan gunakan chunking untuk data besar
        $studentsQuery = Student::with([
            'user:id,full_name',
            'classroom:id,grade,name'
        ])->select('user_id', 'nis', 'class_id');
        
        if ($classId) {
            $studentsQuery->where('class_id', $classId);
        }
        
        $studentData = [];

        // Optimasi: Process dengan chunking untuk data besar (mencegah timeout)
        $studentsQuery->chunk(200, function ($students) use (&$studentData, $dateFromCarbon, $dateToCarbon) {
            foreach ($students as $student) {
                // Optimasi: Single query dengan agregasi untuk menghitung semua status sekaligus
                $stats = Attendance::selectRaw('
                    COUNT(*) as total_records,
                    SUM(CASE WHEN status = "H" THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = "T" THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN status = "A" THEN 1 ELSE 0 END) as absent_count
                ')
                ->where('student_id', $student->user_id)
                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                ->first();

                $studentTotal = $stats->total_records ?? 0;
                $studentPresent = $stats->present_count ?? 0;
                $studentLate = $stats->late_count ?? 0;
                $studentAbsent = $stats->absent_count ?? 0;
                $studentPercentage = $studentTotal > 0 ? round(($studentPresent / $studentTotal) * 100, 2) : 0;

                // Calculate presence status
                $presences = \App\Services\StudentPresenceService::getPresenceStatusForRange(
                    $student->user_id,
                    $dateFromCarbon->toDateString(),
                    $dateToCarbon->toDateString()
                );
                
                // Cek apakah ada presences dengan approval_count dan rejection_count (mixed approval)
                // Mixed approval terjadi jika:
                // 1. Ada presence dengan approval_count > 0 DAN rejection_count > 0 (satu hari dengan mixed)
                // 2. Atau ada beberapa presences dengan approval_count > 0 dan beberapa dengan rejection_count > 0
                $hasApproval = $presences->filter(function($p) {
                    return $p->approval_count > 0;
                })->count() > 0;
                $hasRejection = $presences->filter(function($p) {
                    return $p->rejection_count > 0;
                })->count() > 0;
                $hasMixedInSingleDay = $presences->filter(function($p) {
                    return $p->approval_count > 0 && $p->rejection_count > 0;
                })->count() > 0;
                
                $hasMixedApproval = ($hasApproval && $hasRejection) || $hasMixedInSingleDay;
                
                // Jika ada mixed approval, tampilkan format "4 | 2"
                if ($hasMixedApproval) {
                    $totalApproval = $presences->sum('approval_count');
                    $totalRejection = $presences->sum('rejection_count');
                    $statusKehadiranText = $totalApproval . ' | ' . $totalRejection;
                } else {
                    $hadirCount = $presences->where('status', 'H')->count();
                    $alfaCount = $presences->where('status', 'A')->count();
                    $izinCount = $presences->where('status', 'I')->count();
                    $sakitCount = $presences->where('status', 'S')->count();
                    
                    // Tentukan status dominan berdasarkan prioritas: Alfa > Sakit > Izin > Hadir
                    // Note: Jika semua approve, status sudah menjadi 'H' (Hadir) di observer/service
                    $statusKehadiranText = '-';
                    
                    if ($alfaCount > 0) {
                        $statusKehadiranText = 'Alfa';
                    } elseif ($sakitCount > 0) {
                        $statusKehadiranText = 'Sakit';
                    } elseif ($izinCount > 0) {
                        $statusKehadiranText = 'Izin';
                    } elseif ($hadirCount > 0) {
                        $statusKehadiranText = 'Hadir';
                    }
                }

                $studentData[] = [
                    'name' => $student->user->full_name,
                    'nis' => $student->nis,
                    'grade' => $student->classroom->grade,
                    'class_name' => $student->classroom->name,
                    'status_kehadiran' => $statusKehadiranText,
                    'has_mixed_approval' => $hasMixedApproval,
                    'total_approval' => $hasMixedApproval ? $presences->sum('approval_count') : 0,
                    'total_rejection' => $hasMixedApproval ? $presences->sum('rejection_count') : 0,
                    'present' => $studentPresent,
                    'late' => $studentLate,
                    'absent' => $studentAbsent,
                    'percentage' => $studentPercentage,
                ];
            }
        });

        return $studentData;
    }

    private function getTeacherDataForPDF($dateFromCarbon, $dateToCarbon)
    {
        $teachers = Teacher::with('user')->get();
        $teacherData = [];

        foreach ($teachers as $teacher) {
            // Calculate total pertemuan
            $totalPertemuan = 0;
            $timetables = Timetable::whereHas('classSubject.teacher', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })->get();
            
            $startDate = $dateFromCarbon->copy();
            $endDate = $dateToCarbon->copy();
            $pertemuanMap = [];
            
            while ($startDate->lte($endDate)) {
                $dayOfWeek = $startDate->dayOfWeek;
                $dateStr = $startDate->format('Y-m-d');
                
                $dayTimetables = $timetables->filter(function($t) use ($dayOfWeek) {
                    return $t->day_of_week == $dayOfWeek;
                });
                
                foreach ($dayTimetables as $timetable) {
                    if (!$timetable->classSubject) continue;
                    
                    $subjectName = $timetable->classSubject->subject->name ?? 'N/A';
                    $className = $timetable->classSubject->class->name ?? 'N/A';
                    $key = $subjectName . '_' . $className . '_' . $dateStr;
                    
                    if (!isset($pertemuanMap[$key])) {
                        $pertemuanMap[$key] = true;
                        $totalPertemuan++;
                    }
                }
                
                $startDate->addDay();
            }
            
            // Calculate total record
            $totalRecord = AttendanceSession::whereHas('timetable.classSubject.teacher', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })
            ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
            ->where('is_active', false)
            ->count();
            
            // Calculate presence status
            $presences = \App\Models\TeacherPresence::where('teacher_id', $teacher->user_id)
                ->whereBetween('date', [$dateFromCarbon->toDateString(), $dateToCarbon->toDateString()])
                ->get();
            
            $hadirCount = $presences->where('status', 'H')->count();
            $alfaCount = $presences->where('status', 'A')->count();
            $izinCount = $presences->where('status', 'I')->count();
            $sakitCount = $presences->where('status', 'S')->count();
            
            // Tentukan status dominan berdasarkan prioritas: Alfa > Sakit > Izin > Hadir
            $statusKehadiranText = '-';
            
            if ($alfaCount > 0) {
                $statusKehadiranText = 'Alfa';
            } elseif ($sakitCount > 0) {
                $statusKehadiranText = 'Sakit';
            } elseif ($izinCount > 0) {
                $statusKehadiranText = 'Izin';
            } elseif ($hadirCount > 0) {
                $statusKehadiranText = 'Hadir';
            }

            $teacherData[] = [
                'name' => $teacher->user->full_name ?? 'N/A',
                'nip' => $teacher->nip ?? 'N/A',
                'status_kehadiran' => $statusKehadiranText,
                'total_pertemuan' => $totalPertemuan,
                'total_record' => $totalRecord,
            ];
        }

        return $teacherData;
    }

    public function getTeacherDetail(Request $request)
    {
        try {
            $teacherId = $request->get('teacher_id');
            $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

            if (!$teacherId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher ID is required'
                ], 400);
            }

            $teacher = Teacher::with('user')->where('user_id', $teacherId)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }

            $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
            $dateToCarbon = Carbon::parse($dateTo)->endOfDay();

            // Get all timetables for this teacher
            $timetables = Timetable::with(['classSubject.subject', 'classSubject.class', 'classSubject.teacher'])
                ->whereHas('classSubject.teacher', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                })
                ->get();

            // Calculate total pertemuan (unique by subject, class, and date)
            $totalPertemuan = 0;
            $pertemuanMap = [];
            $startDate = Carbon::parse($dateFrom);
            $endDate = Carbon::parse($dateTo);
            
            while ($startDate->lte($endDate)) {
                $dayOfWeek = $startDate->dayOfWeek;
                $dateStr = $startDate->format('Y-m-d');
                
                // Get timetables for this day
                $dayTimetables = $timetables->filter(function($t) use ($dayOfWeek) {
                    return $t->day_of_week == $dayOfWeek;
                });
                
                foreach ($dayTimetables as $timetable) {
                    if (!$timetable->classSubject) continue;
                    
                    $subjectName = $timetable->classSubject->subject->name ?? 'N/A';
                    $className = $timetable->classSubject->class->name ?? 'N/A';
                    
                    // Create unique key based on subject, class, and date
                    $key = $subjectName . '_' . $className . '_' . $dateStr;
                    
                    if (!isset($pertemuanMap[$key])) {
                        $pertemuanMap[$key] = true;
                        $totalPertemuan++;
                    }
                }
                
                $startDate->addDay();
            }

            // Get classes that were attended (have AttendanceSession with is_active = false)
            $attendedSessions = AttendanceSession::with(['timetable.classSubject.subject', 'timetable.classSubject.class'])
                ->whereHas('timetable.classSubject.teacher', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                })
                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                ->where('is_active', false)
                ->get();

            // Group by subject, class, and date to merge duplicates
            // Key: subject_name + class_name + date
            $classesAttended = [];
            $attendedMap = [];
            
            foreach ($attendedSessions as $session) {
                $timetable = $session->timetable;
                if (!$timetable || !$timetable->classSubject) continue;
                
                $subjectName = $timetable->classSubject->subject->name ?? 'N/A';
                $className = $timetable->classSubject->class->name ?? 'N/A';
                $classGrade = $timetable->classSubject->class->grade ?? 'N/A';
                $date = $session->created_at->format('Y-m-d');
                
                // Create unique key based on subject, class, and date (not timetable_id)
                $key = $subjectName . '_' . $className . '_' . $date;
                
                if (!isset($attendedMap[$key])) {
                    $startTime = Carbon::parse($timetable->start_time)->format('H:i');
                    $endTime = Carbon::parse($timetable->end_time)->format('H:i');
                    
                    $attendedMap[$key] = [
                        'subject_name' => $subjectName,
                        'class_name' => $className,
                        'class_grade' => $classGrade,
                        'date' => $date,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'time_range' => $startTime . ' - ' . $endTime,
                        'total_record' => 0
                    ];
                } else {
                    // Update time range to include earliest start and latest end
                    $currentStart = Carbon::parse($attendedMap[$key]['start_time']);
                    $currentEnd = Carbon::parse($attendedMap[$key]['end_time']);
                    $newStart = Carbon::parse($timetable->start_time);
                    $newEnd = Carbon::parse($timetable->end_time);
                    
                    if ($newStart->lt($currentStart)) {
                        $attendedMap[$key]['start_time'] = $newStart->format('H:i');
                    }
                    if ($newEnd->gt($currentEnd)) {
                        $attendedMap[$key]['end_time'] = $newEnd->format('H:i');
                    }
                    $attendedMap[$key]['time_range'] = $attendedMap[$key]['start_time'] . ' - ' . $attendedMap[$key]['end_time'];
                }
                
                // Count attendances for this session
                $attendanceCount = Attendance::where('session_id', $session->id)->count();
                $attendedMap[$key]['total_record'] += $attendanceCount;
            }
            
            // Sort by date and subject name
            $classesAttended = array_values($attendedMap);
            usort($classesAttended, function($a, $b) {
                $dateCompare = strcmp($a['date'], $b['date']);
                if ($dateCompare !== 0) return $dateCompare;
                return strcmp($a['subject_name'], $b['subject_name']);
            });

            // Get classes that were NOT attended
            // These are timetables that should have had sessions but don't
            // Group by subject, class, and date to merge duplicates
            // IMPORTANT: If a class (subject+class+date) already has attendance session, 
            // all timetables for that class on that date are considered attended
            $classesNotAttended = [];
            $notAttendedMap = [];
            $startDate = Carbon::parse($dateFrom);
            $endDate = Carbon::parse($dateTo);
            
            while ($startDate->lte($endDate)) {
                $dayOfWeek = $startDate->dayOfWeek;
                $dateStr = $startDate->format('Y-m-d');
                
                // Get timetables for this day
                $dayTimetables = $timetables->filter(function($t) use ($dayOfWeek) {
                    return $t->day_of_week == $dayOfWeek;
                });
                
                foreach ($dayTimetables as $timetable) {
                    if (!$timetable->classSubject) continue;
                    
                    $subjectName = $timetable->classSubject->subject->name ?? 'N/A';
                    $className = $timetable->classSubject->class->name ?? 'N/A';
                    
                    // Create unique key based on subject, class, and date (same as attendedMap)
                    $key = $subjectName . '_' . $className . '_' . $dateStr;
                    
                    // Check if this class (subject+class+date) already has attendance session
                    // If it exists in attendedMap, skip it (already attended)
                    if (isset($attendedMap[$key])) {
                        continue; // This class already has attendance, skip
                    }
                    
                    // Check if there's any AttendanceSession for this subject+class+date combination
                    // (not just for this specific timetable_id, but for any timetable with same subject+class+date)
                    $hasSessionForClass = AttendanceSession::whereHas('timetable.classSubject', function($q) use ($subjectName, $className, $teacherId) {
                        $q->whereHas('subject', function($sq) use ($subjectName) {
                            $sq->where('name', $subjectName);
                        })
                        ->whereHas('class', function($cq) use ($className) {
                            $cq->where('name', $className);
                        })
                        ->whereHas('teacher', function($tq) use ($teacherId) {
                            $tq->where('teacher_id', $teacherId);
                        });
                    })
                        ->whereDate('created_at', $dateStr)
                        ->where('is_active', false)
                        ->exists();
                    
                    if (!$hasSessionForClass) {
                        $classGrade = $timetable->classSubject->class->grade ?? 'N/A';
                        
                        if (!isset($notAttendedMap[$key])) {
                            $startTime = Carbon::parse($timetable->start_time)->format('H:i');
                            $endTime = Carbon::parse($timetable->end_time)->format('H:i');
                            
                            $notAttendedMap[$key] = [
                                'subject_name' => $subjectName,
                                'class_name' => $className,
                                'class_grade' => $classGrade,
                                'day_of_week' => $dayOfWeek,
                                'start_time' => $startTime,
                                'end_time' => $endTime,
                                'time_range' => $startTime . ' - ' . $endTime,
                                'date' => $dateStr
                            ];
                        } else {
                            // Update time range to include earliest start and latest end
                            $currentStart = Carbon::parse($notAttendedMap[$key]['start_time']);
                            $currentEnd = Carbon::parse($notAttendedMap[$key]['end_time']);
                            $newStart = Carbon::parse($timetable->start_time);
                            $newEnd = Carbon::parse($timetable->end_time);
                            
                            if ($newStart->lt($currentStart)) {
                                $notAttendedMap[$key]['start_time'] = $newStart->format('H:i');
                            }
                            if ($newEnd->gt($currentEnd)) {
                                $notAttendedMap[$key]['end_time'] = $newEnd->format('H:i');
                            }
                            $notAttendedMap[$key]['time_range'] = $notAttendedMap[$key]['start_time'] . ' - ' . $notAttendedMap[$key]['end_time'];
                        }
                    }
                }
                
                $startDate->addDay();
            }
            
            // Sort by date and subject name
            $classesNotAttended = array_values($notAttendedMap);
            usort($classesNotAttended, function($a, $b) {
                $dateCompare = strcmp($a['date'], $b['date']);
                if ($dateCompare !== 0) return $dateCompare;
                return strcmp($a['subject_name'], $b['subject_name']);
            });

            // Get total record count
            $totalRecord = AttendanceSession::whereHas('timetable.classSubject.teacher', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                })
                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                ->where('is_active', false)
                ->count();

            return response()->json([
                'success' => true,
                'teacher' => [
                    'id' => $teacher->user_id,
                    'name' => $teacher->user->full_name ?? 'N/A',
                    'nip' => $teacher->nip ?? 'N/A'
                ],
                'summary' => [
                    'total_pertemuan' => $totalPertemuan,
                    'total_record' => $totalRecord
                ],
                'classes_attended' => $classesAttended,
                'classes_not_attended' => $classesNotAttended
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting teacher detail: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data detail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentDetail(Request $request)
    {
        try {
            $studentId = $request->get('student_id');
            $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

            if (!$studentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID is required'
                ], 400);
            }

            $student = Student::with('user', 'classroom')->where('user_id', $studentId)->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
            $dateToCarbon = Carbon::parse($dateTo)->endOfDay();

            // Get all attendance sessions opened in the date range for student's class
            $attendanceSessions = AttendanceSession::with(['timetable.classSubject.subject', 'timetable.classSubject.class'])
                ->whereHas('timetable.classSubject', function($query) use ($student) {
                    $query->where('class_id', $student->class_id);
                })
                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                ->where('is_active', false) // Only closed sessions
                ->get();

            // Group by date
            $dailyData = [];
            $currentDate = $dateFromCarbon->copy();
            
            while ($currentDate->lte($dateToCarbon)) {
                $dateStr = $currentDate->format('Y-m-d');
                $dayOfWeek = $currentDate->dayOfWeek;
                
                // Get sessions opened on this date
                $daySessions = $attendanceSessions->filter(function($session) use ($currentDate) {
                    return Carbon::parse($session->created_at)->isSameDay($currentDate);
                });

                // Get student presence for this date
                $presence = StudentPresence::where('student_id', $studentId)
                    ->whereDate('date', $currentDate)
                    ->first();

                // Get attendances for this student on this date
                $attendances = Attendance::where('student_id', $studentId)
                    ->whereDate('created_at', $currentDate)
                    ->with(['attendanceSession.timetable.classSubject.subject'])
                    ->get();

                // Determine default status for the day based on presence
                $dayDefaultStatus = null;
                $dayDefaultStatusText = null;
                $dayDefaultStatusBadge = null;
                
                if ($presence) {
                    // If presence status is Izin/Sakit/Alfa, use that for all subjects
                    if (in_array($presence->status, ['I', 'S', 'A'])) {
                        $dayDefaultStatus = $presence->status;
                        $dayDefaultStatusText = match($presence->status) {
                            'I' => 'Izin',
                            'S' => 'Sakit',
                            'A' => 'Alfa',
                            default => 'Tidak Masuk'
                        };
                        $dayDefaultStatusBadge = match($presence->status) {
                            'I' => 'info',
                            'S' => 'warning',
                            'A' => 'danger',
                            default => 'danger'
                        };
                    }
                }

                // Group sessions by subject
                $subjectsData = [];
                foreach ($daySessions as $session) {
                    if (!$session->timetable || !$session->timetable->classSubject) continue;
                    
                    $subjectName = $session->timetable->classSubject->subject->name ?? 'N/A';
                    $subjectId = $session->timetable->classSubject->subject_id ?? null;
                    
                    // Check if student has attendance for this session
                    $attendance = $attendances->firstWhere('session_id', $session->id);
                    
                    // Determine status
                    $status = 'A'; // Default: Tidak Masuk
                    $statusText = 'Tidak Masuk';
                    $statusBadge = 'danger';
                    
                    // If day has default status (Izin/Sakit/Alfa), use that
                    if ($dayDefaultStatus) {
                        $status = $dayDefaultStatus;
                        $statusText = $dayDefaultStatusText;
                        $statusBadge = $dayDefaultStatusBadge;
                    } elseif ($attendance) {
                        // Has attendance record
                        if ($attendance->status == 'H') {
                            $status = 'H';
                            $statusText = 'Masuk';
                            $statusBadge = 'success';
                        } elseif ($attendance->status == 'T') {
                            $status = 'T';
                            $statusText = 'Terlambat';
                            $statusBadge = 'warning';
                        } else {
                            $status = $attendance->status;
                            $statusText = match($attendance->status) {
                                'I' => 'Izin',
                                'S' => 'Sakit',
                                'A' => 'Tidak Masuk',
                                default => 'Tidak Masuk'
                            };
                            $statusBadge = match($attendance->status) {
                                'I' => 'info',
                                'S' => 'warning',
                                'A' => 'danger',
                                default => 'danger'
                            };
                        }
                    }

                    // Group by subject (if same subject appears multiple times, merge)
                    $key = $subjectId . '_' . $subjectName;
                    if (!isset($subjectsData[$key])) {
                        $subjectsData[$key] = [
                            'subject_name' => $subjectName,
                            'status' => $status,
                            'status_text' => $statusText,
                            'status_badge' => $statusBadge,
                            'check_in_time' => $attendance ? ($attendance->check_in_time ?? null) : null,
                            'count' => 1
                        ];
                    } else {
                        // If already has better status (H > T > A), keep it
                        $currentStatus = $subjectsData[$key]['status'];
                        $statusPriority = ['H' => 3, 'T' => 2, 'I' => 1, 'S' => 1, 'A' => 0];
                        if (($statusPriority[$status] ?? 0) > ($statusPriority[$currentStatus] ?? 0)) {
                            $subjectsData[$key]['status'] = $status;
                            $subjectsData[$key]['status_text'] = $statusText;
                            $subjectsData[$key]['status_badge'] = $statusBadge;
                            if ($attendance && $attendance->check_in_time) {
                                $subjectsData[$key]['check_in_time'] = $attendance->check_in_time;
                            }
                        }
                        $subjectsData[$key]['count']++;
                    }
                }

                if (count($subjectsData) > 0) {
                    $dailyData[] = [
                        'date' => $dateStr,
                        'date_display' => $currentDate->format('d/m/Y'),
                        'total_record' => $daySessions->count(),
                        'subjects' => array_values($subjectsData)
                    ];
                }

                $currentDate->addDay();
            }

            return response()->json([
                'success' => true,
                'student' => [
                    'name' => $student->user->full_name,
                    'nis' => $student->nis,
                    'class' => $student->classroom ? ($student->classroom->grade . ' - ' . $student->classroom->name) : 'N/A'
                ],
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'daily_data' => $dailyData
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting student detail: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data detail: ' . $e->getMessage()
            ], 500);
        }
    }
}
