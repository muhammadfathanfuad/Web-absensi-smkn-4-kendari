<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        if (!empty($row['kode guru'])) {
            $user = User::create([
                'full_name' => $row['nama guru'] ?? '',
                'username' => null,
                'password_hash' => Hash::make('password'),
                'status' => 'active',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $row['NIP'],
            ]);

            $role = Role::where('name', 'teacher')->first();
            if ($role) {
                $user->roles()->attach($role);
            }

            return $user;
        }

        if (!empty($row['kode_siswa'])) {
            $user = User::create([
                'full_name' => $row['nama_siswa'] ?? '',
                'username' => $row['kode_siswa'],
                'password_hash' => Hash::make('password'),
                'status' => 'active',
            ]);

            Student::create([
                'user_id' => $user->id,
                'nis' => $row['kode_siswa'],
            ]);

            $role = Role::where('name', 'student')->first();
            if ($role) {
                $user->roles()->attach($role);
            }

            return $user;
        }

        // If neither kode_guru nor kode_siswa, skip
        return null;
    }

    public function rules(): array
    {
        return [
            'nama_guru' => 'nullable|string',
            'nama_siswa' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'username' => 'nullable|string|unique:users,username',
            'password' => 'nullable|string',
            'kode_guru' => 'nullable|string',
            'kode_siswa' => 'nullable|string',
        ];
    }
}
