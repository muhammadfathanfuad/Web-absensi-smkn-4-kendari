<?php

namespace App\Imports;

use App\Models\Timetable;
use App\Models\Term;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassSubject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalPelajaranImport implements ToCollection
{
    private $term;

    public function __construct()
    {
        // Menggunakan tahun ajaran yang sedang aktif
        $this->term = Term::where('is_active', true)->latest()->first();
        if (!$this->term) {
            // Jika tidak ada, buat default. Sebaiknya admin membuatnya secara manual.
            $this->term = Term::firstOrCreate(['name' => 'Ganjil 2025/2026', 'is_active' => true]);
        }
    }

    public function collection(Collection $rows)
    {
        $headerRowIndex = $this->findHeaderRow($rows);
        if ($headerRowIndex === null) {
            Log::error('Baris header berisi nama kelas tidak ditemukan.');
            throw new \Exception("Format file tidak valid. Pastikan ada baris yang berisi nama-nama kelas (contoh: TKJA, RPLB).");
        }

        $headerRow = $rows[$headerRowIndex];
        $classColumns = $this->getClassroomColumns($headerRow);

        $currentDay = '';
        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex <= $headerRowIndex) continue;

            $rowArray = $row->toArray();

            if (!empty($rowArray[0]) && in_array(strtoupper($rowArray[0]), ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'])) {
                $currentDay = strtolower($rowArray[0]);
            }

            if (empty($currentDay) || empty($rowArray[2]) || !str_contains($rowArray[2], '-')) continue;

            try {
                list($startTimeStr, $endTimeStr) = array_map('trim', explode('-', $rowArray[2]));
                $startTime = Carbon::createFromFormat('H.i', $startTimeStr)->format('H:i:s');
                $endTime = Carbon::createFromFormat('H.i', $endTimeStr)->format('H:i:s');
            } catch (\Exception $e) {
                Log::warning("Format waktu salah pada baris {$rowIndex}: '{$rowArray[2]}'. Baris dilewati.");
                continue;
            }

            foreach ($classColumns as $columnIndex => $classroomId) {
                if (!isset($rowArray[$columnIndex]) || empty($rowArray[$columnIndex])) continue;

                $cellValue = trim($rowArray[$columnIndex]);
                if (!str_contains($cellValue, '/')) continue;

                list($subjectCode, $teacherCode) = array_map('trim', explode('/', $cellValue));

                try {
                    $subject = Subject::where('code', $subjectCode)->first();
                    if (!$subject) {
                        Log::warning("Mapel kode '{$subjectCode}' tidak ditemukan (baris: {$rowIndex}).");
                        continue;
                    }

                    $teacher = Teacher::where('code', $teacherCode)->first();
                    if (!$teacher) {
                        Log::warning("Guru kode '{$teacherCode}' tidak ditemukan (baris: {$rowIndex}).");
                        continue;
                    }

                    $classSubject = ClassSubject::firstOrCreate([
                        'class_id' => $classroomId,
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                    ]);

                    Timetable::updateOrCreate([
                        'term_id' => $this->term->id,
                        'class_subject_id' => $classSubject->id,
                        'day_of_week' => $currentDay,
                        'start_time' => $startTime,
                    ], ['end_time' => $endTime]);
                } catch (\Exception $e) {
                    Log::error("Gagal impor sel: [{$rowIndex}, {$columnIndex}] dengan nilai '{$cellValue}'. Error: " . $e->getMessage());
                }
            }
        }
    }

    private function findHeaderRow(Collection $rows): ?int
    {
        foreach ($rows as $index => $row) {
            // Heuristik: Baris header adalah yang mengandung nama kelas yang ada di DB.
            if ($row->contains('TKJA') || $row->contains('RPLA') || $row->contains('DKVA') || $row->contains('RPLB')) {
                return $index;
            }
        }
        return null;
    }

    private function getClassroomColumns(Collection $headerRow): array
    {
        $classColumns = [];
        foreach ($headerRow as $columnIndex => $columnValue) {
            if (!empty($columnValue)) {
                $classroom = Classroom::where('name', trim($columnValue))->first();
                if ($classroom) {
                    $classColumns[$columnIndex] = $classroom->id;
                }
            }
        }
        return $classColumns;
    }
}
