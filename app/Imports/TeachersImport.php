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

    public function collection(Collection $rows)
    {
        $this->errors = [];
        $this->successCount = 0;
        $this->skipCount = 0;

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena index mulai dari 0 dan ada header row
            
            try {
                // Skip empty rows
        if (empty(trim($row['nama_guru'] ?? ''))) {
                    $this->skipCount++;
                    continue;
                }

                $namaGuru = trim($row['nama_guru'] ?? '');
                $kodeGuru = trim($row['kode_guru'] ?? '');
                $email = trim($row['email'] ?? '');
                $nip = trim($row['nip'] ?? '');
                $noHp = trim($row['no_hp'] ?? '');
                $department = trim($row['department'] ?? '');

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
