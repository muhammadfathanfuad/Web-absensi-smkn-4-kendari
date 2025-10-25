<?php

namespace App\Imports;

use App\Models\Classroom;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class ClassesImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $processedCount = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty($row['nama_kelas']) && empty($row['grade'])) {
                    continue;
                }

                $className = trim($row['nama_kelas']);
                $grade = trim($row['grade']);

                // Convert grade to string if it's numeric
                if (is_numeric($grade)) {
                    $grade = (string) $grade;
                }

                // Validate required fields
                if (empty($className) || empty($grade)) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Nama kelas dan grade harus diisi";
                    continue;
                }

                // Validate grade
                if (!in_array($grade, ['10', '11', '12'])) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Grade harus 10, 11, atau 12 (ditemukan: {$grade})";
                    continue;
                }

                // Check if class already exists
                $existingClass = Classroom::where('name', $className)
                    ->where('grade', $grade)
                    ->first();

                if ($existingClass) {
                    Log::info("Class '{$className}' with grade '{$grade}' already exists, skipping");
                    continue;
                }

                // Create new class
                Classroom::create([
                    'name' => $className,
                    'grade' => $grade,
                ]);

                $this->processedCount++;
                Log::info("Created class: {$className} with grade {$grade}");

            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.nama_kelas' => 'required|string|max:255',
            '*.grade' => 'required|in:10,11,12',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_kelas.required' => 'Nama kelas harus diisi',
            '*.nama_kelas.string' => 'Nama kelas harus berupa teks',
            '*.nama_kelas.max' => 'Nama kelas maksimal 255 karakter',
            '*.grade.required' => 'Grade harus diisi',
            '*.grade.string' => 'Grade harus berupa teks',
            '*.grade.in' => 'Grade harus 10, 11, atau 12',
        ];
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
