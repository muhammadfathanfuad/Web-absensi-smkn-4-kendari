<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeachersImport implements ToCollection, WithHeadingRow
{
    private $errors = [];
    private $successCount = 0;
    private $skipCount = 0;
    private $initialized = false;

    public function collection(Collection $rows)
    {
        // Only initialize counters once (on first call)
        // With chunking, this method may be called multiple times
        if (!$this->initialized) {
            $this->errors = [];
            $this->successCount = 0;
            $this->skipCount = 0;
            $this->initialized = true;
            Log::info('TeachersImport: Initialized counters', ['total_rows' => $rows->count()]);
        }

        Log::info('TeachersImport: Processing chunk', [
            'chunk_size' => $rows->count(),
            'current_success' => $this->successCount,
            'current_errors' => count($this->errors),
            'current_skip' => $this->skipCount
        ]);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena index mulai dari 0 dan ada header row
            
            try {
                // Normalize row keys to handle case sensitivity and spaces
                $normalizedRow = [];
                foreach ($row as $key => $value) {
                    $normalizedKey = strtolower(trim(str_replace([' ', '_'], '', $key)));
                    $normalizedRow[$normalizedKey] = $value;
                }
                
                // Map normalized keys to expected keys (try multiple variations)
                $namaGuru = trim($normalizedRow['namaguru'] ?? $row['nama_guru'] ?? $row['Nama Guru'] ?? $row['nama guru'] ?? $row['NamaGuru'] ?? '');
                $kodeGuru = trim($normalizedRow['kodeguru'] ?? $row['kode_guru'] ?? $row['Kode Guru'] ?? $row['kode guru'] ?? $row['KodeGuru'] ?? '');
                $email = trim($normalizedRow['email'] ?? $row['email'] ?? $row['Email'] ?? $row['EMAIL'] ?? '');
                $nip = trim($normalizedRow['nip'] ?? $row['nip'] ?? $row['NIP'] ?? '');
                $noHp = trim($normalizedRow['nohp'] ?? $normalizedRow['telepon'] ?? $row['no_hp'] ?? $row['No. HP'] ?? $row['no hp'] ?? $row['telepon'] ?? $row['Telepon'] ?? '');
                $department = trim($normalizedRow['department'] ?? $normalizedRow['matapelajaran'] ?? $row['department'] ?? $row['Department'] ?? $row['Mata Pelajaran'] ?? $row['mata pelajaran'] ?? '');
                
                // Log first row for debugging
                if ($index === 0) {
                    Log::info('TeachersImport: First row sample', [
                        'row_keys' => array_keys($row->toArray()),
                        'normalized_keys' => array_keys($normalizedRow),
                        'nama_guru' => $namaGuru,
                        'kode_guru' => $kodeGuru,
                        'email' => $email
                    ]);
                }
                
                // Skip empty rows
                if (empty($namaGuru)) {
                    $this->skipCount++;
                    continue;
                }

                // Validasi field wajib
                $validationErrors = [];
                
                if (empty($namaGuru)) {
                    $validationErrors[] = "Nama Guru harus diisi";
                }
                
                if (empty($kodeGuru)) {
                    $validationErrors[] = "Kode Guru harus diisi";
        }

                if (empty($email)) {
                    $validationErrors[] = "Email harus diisi";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validationErrors[] = "Format email tidak valid";
                }

                if (!empty($validationErrors)) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'nama_guru' => $namaGuru ?: '(kosong)',
                        'kode_guru' => $kodeGuru ?: '(kosong)',
                        'errors' => $validationErrors
                    ];
                    continue;
                }

                // Check if kode_guru already exists
                if (Teacher::where('kode_guru', $kodeGuru)->exists()) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'nama_guru' => $namaGuru,
                        'kode_guru' => $kodeGuru,
                        'errors' => ["Kode Guru '{$kodeGuru}' sudah ada di database (duplikat)"]
                    ];
                    continue;
                }

                // Check if email already exists
                $existingUser = User::where('email', $email)->first();
                if ($existingUser && $existingUser->teacher) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'nama_guru' => $namaGuru,
                        'kode_guru' => $kodeGuru,
                        'errors' => ["Email '{$email}' sudah terdaftar sebagai guru"]
                    ];
                    continue;
        }

                // Create or get user
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'full_name' => $namaGuru,
                        'phone' => $noHp,
                        'username' => $kodeGuru,
            'password_hash' => Hash::make('password'),
            'status' => 'suspended',
                    ]
                );

                // Update user if it already exists but not a teacher
                if ($user->wasRecentlyCreated === false) {
                    $user->update([
                        'full_name' => $namaGuru,
                        'phone' => $noHp,
                        'username' => $kodeGuru,
                    ]);
                }

                // Create teacher if doesn't exist
        if (!$user->teacher) {
            Teacher::create([
                'user_id' => $user->id,
                        'kode_guru' => $kodeGuru,
                        'nip' => $nip,
                        'department' => $department,
            ]);

            // Assign role teacher
            $role = Role::where('name', 'teacher')->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching($role);
            }
                } else {
                    // Teacher already exists for this user
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'nama_guru' => $namaGuru,
                        'kode_guru' => $kodeGuru,
                        'errors' => ["User dengan email '{$email}' sudah memiliki data guru"]
                    ];
                    continue;
                }

                $this->successCount++;
                Log::info("Successfully imported teacher: {$namaGuru} (Kode: {$kodeGuru})");

            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'nama_guru' => $row['nama_guru'] ?? '(tidak diketahui)',
                    'kode_guru' => $row['kode_guru'] ?? '(tidak diketahui)',
                    'errors' => ["Error: " . $e->getMessage()]
                ];
                Log::error("Error importing teacher at row {$rowNumber}: " . $e->getMessage());
            }
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
