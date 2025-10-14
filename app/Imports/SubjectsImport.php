<?php

namespace App\Imports;

use App\Models\Subject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class SubjectsImport implements ToCollection
{
    private $processedCount = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        $this->processedCount = 0;
        $this->errors = [];

        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                // Convert row to array for easier handling
                $arr = $row->toArray();
                
                // Get code and name from first two columns
                $code = trim((string)($arr[0] ?? ''));
                $name = trim((string)($arr[1] ?? ''));

                // Skip header rows
                if (strtolower($code) === 'kode mapel' || strtolower($code) === 'kode mata pelajaran' || 
                    strtolower($name) === 'mata pelajaran' || strtolower($name) === 'mata pelajaran' ||
                    strtolower($code) === 'kode' || strtolower($name) === 'nama') {
                    continue;
                }

                // Skip if both code and name are empty
                if (empty($code) && empty($name)) {
                    continue;
                }

                // Validate required fields
                if (empty($code)) {
                    $this->errors[] = "Baris " . ($index + 1) . ": Kode mata pelajaran harus diisi";
                    continue;
                }
                
                if (empty($name)) {
                    $this->errors[] = "Baris " . ($index + 1) . ": Nama mata pelajaran harus diisi";
                    continue;
                }

                // Validate code format (should be alphanumeric)
                if (!preg_match('/^[A-Z0-9]+$/', $code)) {
                    $this->errors[] = "Baris " . ($index + 1) . ": Kode mata pelajaran harus berupa huruf dan angka (ditemukan: {$code})";
                    continue;
                }

                // Create or update subject
                Subject::updateOrCreate(
                    ['code' => $code],
                    ['name' => $name]
                );

                $this->processedCount++;
                Log::info("Imported subject: {$code} - {$name}");

            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                Log::error("Error importing subject at row " . ($index + 1) . ": " . $e->getMessage());
            }
        }
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

