<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Mempersiapkan data sebelum validasi.
     * Jika 'nama_guru' kosong, baris ini akan dilewati saat validasi.
     */
    public function prepareForValidation(array $row, int $rowIndex): array
    {
        // Jika nama_guru kosong, kita anggap baris ini tidak valid dan harus dilewati
        if (empty($row['nama_guru'])) {
            // Mengosongkan semua field agar aturan validasi 'nullable' bisa berlaku
            // dan tidak memicu error 'required' pada 'nama_guru'.
            $row['nama_guru'] = null;
        }

        // Cast kode_guru, nip, dan no_hp ke string jika ada
        if (isset($row['kode_guru']) && $row['kode_guru'] !== null) {
            $row['kode_guru'] = (string) $row['kode_guru'];
        }
        if (isset($row['nip']) && $row['nip'] !== null) {
            $row['nip'] = (string) $row['nip'];
        }
        if (isset($row['no_hp']) && $row['no_hp'] !== null) {
            $row['no_hp'] = (string) $row['no_hp'];
        }

        return $row;
    }

    public function model(array $row)
    {
        // Check if nama guru is present
        if (!empty($row['nama_guru'])) {
            $user = User::create([
                'full_name' => $row['nama_guru'] ?? '',
                'email' => $row['email'] ?? null,
                'phone' => $row['no_hp'] ?? null,
                'username' => null,
                'password_hash' => Hash::make('password'),
                'status' => 'suspended',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'kode_guru' => $row['kode_guru'] ?? null,
                'nip' => $row['nip'] ?? null,
                'department' => $row['department'] ?? null,
            ]);

            // Assign role teacher
            $role = Role::where('name', 'teacher')->first();
            if ($role) {
                $user->roles()->attach($role);
            }

            return $user;
        }


        return null;
    }

    public function rules(): array
    {
        return [
            'kode_guru' => 'nullable|string|unique:teachers,kode_guru',
            'nama_guru' => 'nullable|required_with:kode_guru,nip,email,no_hp|string',
            'nip' => 'nullable|string|unique:teachers,nip',
            'email' => 'nullable|email|unique:users,email',
            'no_hp' => 'nullable|string|unique:users,phone',
            'department' => 'nullable|string',
        ];
    }
}
