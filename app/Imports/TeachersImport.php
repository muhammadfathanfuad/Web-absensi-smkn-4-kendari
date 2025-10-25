<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class TeachersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Columns: kode_guru, nama_guru, nip, email, no_hp, department
        if (empty(trim($row['nama_guru'] ?? ''))) {
            return null;
        }

        if (Teacher::where('kode_guru', trim($row['kode_guru'] ?? ''))->exists()) {
            return null;
        }

        $user = User::firstOrCreate([
            'email' => trim($row['email'] ?? null),
        ], [
            'full_name' => trim($row['nama_guru']),
            'phone' => trim($row['no_hp'] ?? ''),
            'username' => trim($row['kode_guru'] ?? null),
            'password_hash' => Hash::make('password'),
            'status' => 'suspended',
        ]);

        if (!$user->teacher) {
            Teacher::create([
                'user_id' => $user->id,
                'kode_guru' => trim($row['kode_guru'] ?? ''),
                'nip' => trim($row['nip'] ?? ''),
                'department' => trim($row['department'] ?? ''),
            ]);

            // Assign role teacher
            $role = Role::where('name', 'teacher')->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching($role);
            }
        }

        return $user;
    }
}
