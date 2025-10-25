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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\TimeOverrideService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
            $classId = $request->get('class_id');
            $subjectId = $request->get('subject_id');
            $teacherId = $request->get('teacher_id');
            $reportType = $request->get('report_type', 'overview');

            // Get basic data for filters
            $classes = Classroom::with('students')->get();
            $subjects = Subject::all();
            $teachers = Teacher::with('user')->get();

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
            $format = $request->get('format', 'xlsx');
            if (!in_array($format, ['xlsx', 'csv'])) {
                $format = 'xlsx';
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

            // Get simple data based on report type
            $data = $this->getSimpleExportData($reportType, $dateFrom, $dateTo, $classId, $subjectId, $teacherId);

            Log::info('Export data prepared', ['data_count' => count($data)]);

            // Create export instance
            $export = new AttendanceReportExport($data, $reportType, $dateFrom, $dateTo);

            // Return Excel file with proper headers
            if ($format === 'xlsx') {
                Log::info('Returning Excel file', ['filename' => $filename]);
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX, [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            } else {
                Log::info('Returning CSV file', ['filename' => $filename]);
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::CSV, [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            }
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
                return $this->getSimpleStudentData();
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
        $totalRecords = Attendance::count();
        $presentCount = Attendance::where('status', 'H')->count();
        $lateCount = Attendance::where('status', 'T')->count();
        $absentCount = Attendance::where('status', 'A')->count();
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
        $classes = Classroom::with('students')->get();
        $classSummary = collect();

        foreach ($classes as $class) {
            $totalRecords = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })->count();

            $presentCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })->where('status', 'H')->count();

            $lateCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })->where('status', 'T')->count();

            $absentCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })->where('status', 'A')->count();

            $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

            $classSummary->push((object)[
                'grade' => $class->grade,
                'class_name' => $class->name,
                'total_students' => $class->students->count(),
                'total_records' => $totalRecords,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $absentCount,
                'attendance_percentage' => $attendancePercentage
            ]);
        }

        return ['class_summary' => $classSummary];
    }

    private function getSimpleStudentData()
    {
        $students = Student::with(['user', 'classroom'])->get();
        $studentSummary = collect();

        foreach ($students as $student) {
            $totalRecords = Attendance::where('student_id', $student->user_id)->count();
            $presentCount = Attendance::where('student_id', $student->user_id)->where('status', 'H')->count();
            $lateCount = Attendance::where('student_id', $student->user_id)->where('status', 'T')->count();
            $absentCount = Attendance::where('student_id', $student->user_id)->where('status', 'A')->count();
            $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

            $studentSummary->push((object)[
                'full_name' => $student->user->full_name,
                'nis' => $student->nis,
                'grade' => $student->classroom->grade,
                'class_name' => $student->classroom->name,
                'total_records' => $totalRecords,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $absentCount,
                'attendance_percentage' => $attendancePercentage
            ]);
        }

        return ['student_summary' => $studentSummary];
    }

    private function getSimpleSubjectData()
    {
        $subjects = Subject::all();
        $subjectSummary = collect();

        foreach ($subjects as $subject) {
            $totalRecords = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })->count();

            $presentCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })->where('status', 'H')->count();

            $lateCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })->where('status', 'T')->count();

            $absentCount = Attendance::whereHas('classSession.timetable.classSubject', function ($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })->where('status', 'A')->count();

            $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;

            $subjectSummary->push((object)[
                'subject_name' => $subject->name,
                'subject_code' => $subject->code,
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
}
