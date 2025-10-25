<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceReportExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $reportType;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($data, $reportType, $dateFrom, $dateTo)
    {
        $this->data = $data;
        $this->reportType = $reportType;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function array(): array
    {
        switch ($this->reportType) {
            case 'overview':
                return $this->getOverviewData();
            case 'class':
                return $this->getClassData();
            case 'student':
                return $this->getStudentData();
            case 'subject':
                return $this->getSubjectData();
            case 'teacher':
                return $this->getTeacherData();
            default:
                return [];
        }
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'overview':
                return [
                    'Tanggal',
                    'Total Record',
                    'Hadir',
                    'Terlambat',
                    'Absen',
                    'Persentase Hadir (%)'
                ];
            case 'class':
                return [
                    'Kelas',
                    'Total Siswa',
                    'Total Record',
                    'Hadir',
                    'Terlambat',
                    'Absen',
                    'Persentase Hadir (%)'
                ];
            case 'student':
                return [
                    'Nama Siswa',
                    'NIS',
                    'Kelas',
                    'Total Record',
                    'Hadir',
                    'Terlambat',
                    'Absen',
                    'Persentase Hadir (%)'
                ];
            case 'subject':
                return [
                    'Mata Pelajaran',
                    'Kode',
                    'Total Siswa',
                    'Total Record',
                    'Hadir',
                    'Terlambat',
                    'Absen',
                    'Persentase Hadir (%)'
                ];
            case 'teacher':
                return [
                    'Nama Guru',
                    'NIP',
                    'Total Mata Pelajaran',
                    'Total Kelas',
                    'Total Record',
                    'Hadir',
                    'Terlambat',
                    'Absen',
                    'Persentase Hadir (%)'
                ];
            default:
                return [];
        }
    }

    public function title(): string
    {
        $titles = [
            'overview' => 'Ringkasan Kehadiran',
            'class' => 'Laporan Per Kelas',
            'student' => 'Laporan Per Siswa',
            'subject' => 'Laporan Per Mata Pelajaran',
            'teacher' => 'Laporan Per Guru'
        ];

        return $titles[$this->reportType] ?? 'Laporan Kehadiran';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings) as bold
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function getOverviewData(): array
    {
        $data = [];
        
        // Add summary data
        if (isset($this->data['summary'])) {
            $summary = $this->data['summary'];
            $data[] = [
                'TOTAL',
                $summary['total_records'],
                $summary['present_count'],
                $summary['late_count'],
                $summary['absent_count'],
                $summary['present_percentage']
            ];
        }

        return $data;
    }

    private function getClassData(): array
    {
        $data = [];
        
        if (isset($this->data['class_summary'])) {
            foreach ($this->data['class_summary'] as $class) {
                $data[] = [
                    $class->grade . ' - ' . $class->class_name,
                    $class->total_students,
                    $class->total_records,
                    $class->present,
                    $class->late,
                    $class->absent,
                    $class->attendance_percentage
                ];
            }
        }

        return $data;
    }

    private function getStudentData(): array
    {
        $data = [];
        
        if (isset($this->data['student_summary'])) {
            foreach ($this->data['student_summary'] as $student) {
                $data[] = [
                    $student->full_name,
                    $student->nis,
                    $student->grade . ' - ' . $student->class_name,
                    $student->total_records,
                    $student->present,
                    $student->late,
                    $student->absent,
                    $student->attendance_percentage
                ];
            }
        }

        return $data;
    }

    private function getSubjectData(): array
    {
        $data = [];
        
        if (isset($this->data['subject_summary'])) {
            foreach ($this->data['subject_summary'] as $subject) {
                $data[] = [
                    $subject->subject_name,
                    $subject->subject_code,
                    $subject->total_students,
                    $subject->total_records,
                    $subject->present,
                    $subject->late,
                    $subject->absent,
                    $subject->attendance_percentage
                ];
            }
        }

        return $data;
    }

    private function getTeacherData(): array
    {
        $data = [];
        
        if (isset($this->data['teacher_summary'])) {
            foreach ($this->data['teacher_summary'] as $teacher) {
                $data[] = [
                    $teacher->teacher_name,
                    $teacher->nip,
                    $teacher->total_subjects,
                    $teacher->total_classes,
                    $teacher->total_records,
                    $teacher->present,
                    $teacher->late,
                    $teacher->absent,
                    $teacher->attendance_percentage
                ];
            }
        }

        return $data;
    }
}