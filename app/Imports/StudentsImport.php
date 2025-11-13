<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StudentsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $errors = [];
    private $successCount = 0;
    private $skipCount = 0;
    
    public function chunkSize(): int
    {
        return 100; // Process 100 rows at a time
    }

    private function parseKelasTingkatan($kelasString)
    {
        // Default values
        $grade = 10;
        $className = 'Unknown';
        
        if (!empty($kelasString)) {
            // Remove extra spaces and trim
            $kelasString = trim($kelasString);
            
            // Try to extract grade and class name from format "10 TKJA"
            if (preg_match('/^(\d+)\s+(.+)$/', $kelasString, $matches)) {
                // Format: "10 TKJA" -> grade=10, class_name=TKJA
                $grade = (int) $matches[1];
                $className = trim($matches[2]);
            } elseif (preg_match('/^(\d+)([A-Za-z].+)$/', $kelasString, $matches)) {
                // Format: "10TKJA" -> grade=10, class_name=TKJA
                $grade = (int) $matches[1];
                $className = trim($matches[2]);
            } elseif (is_numeric($kelasString)) {
                // Format: "10" -> grade=10, class_name=10
                $grade = (int) $kelasString;
                $className = $kelasString;
            } else {
                // Format: "TKJA" -> grade=10 (default), class_name=TKJA
                $className = $kelasString;
            }
        }
        
        return [
            'grade' => $grade,
            'class_name' => $className
        ];
    }

    public function collection(Collection $rows)
    {
        // Initialize counters if not already set
        if (!isset($this->errors)) {
            $this->errors = [];
        }
        if (!isset($this->successCount)) {
            $this->successCount = 0;
        }
        if (!isset($this->skipCount)) {
            $this->skipCount = 0;
        }

        // Use database transaction for better performance
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index mulai dari 0 dan ada header row
                
                try {
                    // Skip empty rows
                    if (empty(trim($row['nama'] ?? ''))) {
                        $this->skipCount++;
                        continue;
                    }

                    $nama = trim($row['nama'] ?? '');
                    $nis = trim($row['nis'] ?? '');
                    $kelas = trim($row['kelas'] ?? '');
                    $namaWali = trim($row['nama_wali'] ?? '');
                    $teleponWali = trim($row['telepon_wali'] ?? '');

                    // Validasi field wajib
                    $validationErrors = [];
                    
                    if (empty($nama)) {
                        $validationErrors[] = "Nama harus diisi";
                    }
                    
                    if (empty($nis)) {
                        $validationErrors[] = "NIS harus diisi";
                    }
                    
                    if (empty($kelas)) {
                        $validationErrors[] = "Kelas harus diisi";
                    }

                    if (!empty($validationErrors)) {
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'nama' => $nama ?: '(kosong)',
                            'nis' => $nis ?: '(kosong)',
                            'errors' => $validationErrors
                        ];
                        continue;
                    }

                    // Check if student already exists by NIS
                    if (Student::where('nis', $nis)->exists()) {
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'nama' => $nama,
                            'nis' => $nis,
                            'errors' => ["NIS '{$nis}' sudah ada di database (duplikat)"]
                        ];
                        continue;
            }

            // Parse kelas and tingkatan from combined format "10 TKJA"
                    $kelasData = $this->parseKelasTingkatan($kelas);
            $className = $kelasData['class_name'];
            $grade = $kelasData['grade'];

                    // Validate grade
                    if (!in_array($grade, [10, 11, 12])) {
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'nama' => $nama,
                            'nis' => $nis,
                            'errors' => ["Grade harus 10, 11, atau 12 (ditemukan: {$grade})"]
                        ];
                        continue;
                    }

                    // Find or create class
            $class = Classroom::where('name', $className)
                ->where('grade', $grade)
                ->first();
            
            if (!$class) {
                        try {
                $class = Classroom::create([
                    'name' => $className,
                    'grade' => $grade,
                    'homeroom_teacher_id' => null,
                    'room_id' => null
                ]);
                        } catch (\Exception $e) {
                            $this->errors[] = [
                                'row' => $rowNumber,
                                'nama' => $nama,
                                'nis' => $nis,
                                'errors' => ["Gagal membuat kelas: " . $e->getMessage()]
                            ];
                            continue;
                        }
                    }

                    // Generate email from NIS
                    $email = $nis . '@student.smkn4kendari.sch.id';
                    
                    // Check if email already exists
                    $existingUser = User::where('email', $email)->first();
                    if ($existingUser) {
                        if ($existingUser->student) {
                            $this->errors[] = [
                                'row' => $rowNumber,
                                'nama' => $nama,
                                'nis' => $nis,
                                'errors' => ["Email '{$email}' sudah terdaftar sebagai siswa"]
                            ];
                            continue;
                        }
                        if ($existingUser->teacher) {
                            $this->errors[] = [
                                'row' => $rowNumber,
                                'nama' => $nama,
                                'nis' => $nis,
                                'errors' => ["Email '{$email}' sudah terdaftar sebagai guru"]
                            ];
                            continue;
                        }
                    }

                    // Create user
                    try {
                        $user = User::firstOrCreate(
                            ['email' => $email],
                            [
                                'full_name' => $nama,
                'phone' => null,
                                'username' => $nis,
                'password_hash' => Hash::make('password'),
                'status' => 'suspended',
                            ]
                        );

                        // Update user if it already exists but not a student
                        if ($user->wasRecentlyCreated === false) {
                            $user->update([
                                'full_name' => $nama,
                                'username' => $nis,
                            ]);
                        }
                    } catch (\Exception $e) {
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'nama' => $nama,
                            'nis' => $nis,
                            'errors' => ["Gagal membuat user: " . $e->getMessage()]
                        ];
                        continue;
                    }

                    // Create student if doesn't exist
                    if (!$user->student) {
                        try {
            Student::create([
                'user_id' => $user->id,
                                'nis' => $nis,
                'class_id' => $class->id,
                                'guardian_name' => $namaWali ?: null,
                                'guardian_phone' => $teleponWali ?: null,
            ]);

            // Assign role student
            $role = Role::where('name', 'student')->first();
            if ($role) {
                                $user->roles()->syncWithoutDetaching($role);
            }
                        } catch (\Exception $e) {
                            $this->errors[] = [
                                'row' => $rowNumber,
                                'nama' => $nama,
                                'nis' => $nis,
                                'errors' => ["Gagal membuat data siswa: " . $e->getMessage()]
                            ];
                            continue;
                        }
                    } else {
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'nama' => $nama,
                            'nis' => $nis,
                            'errors' => ["User dengan email '{$email}' sudah memiliki data siswa"]
                        ];
                        continue;
                    }

                    $this->successCount++;
                    Log::info("Successfully imported student: {$nama} (NIS: {$nis})");

                } catch (\Exception $e) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'nama' => $row['nama'] ?? '(tidak diketahui)',
                        'nis' => $row['nis'] ?? '(tidak diketahui)',
                        'errors' => ["Error: " . $e->getMessage()]
                    ];
                    Log::error("Error importing student at row {$rowNumber}: " . $e->getMessage());
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in batch import: " . $e->getMessage());
            // Re-throw to be caught by controller
            throw $e;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getSkipCount()
    {
        return $this->skipCount;
    }
}
