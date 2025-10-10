<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function prepareForValidation(array $row, int $rowIndex): array
    {
        // Jika nama_murid kosong, anggap baris ini tidak valid
        if (empty($row['nama_murid'])) {
            $row['nama_murid'] = null;
        }

        // Map alternative column names
        if (isset($row['phone']) && $row['phone'] !== null) {
            $row['no_hp'] = (string) $row['phone'];
        }
        if (isset($row['no_hp_wali']) && $row['no_hp_wali'] !== null) {
            $row['nomor_hp_wali'] = (string) $row['no_hp_wali'];
        }

        // Cast fields to string if present
        if (isset($row['nama_murid']) && $row['nama_murid'] !== null) {
            $row['nama_murid'] = (string) $row['nama_murid'];
        }
        if (isset($row['email']) && $row['email'] !== null) {
            $row['email'] = (string) $row['email'];
        }
        if (isset($row['no_hp']) && $row['no_hp'] !== null) {
            $row['no_hp'] = (string) $row['no_hp'];
        }
        if (isset($row['nis']) && $row['nis'] !== null) {
            $row['nis'] = (string) $row['nis'];
        }
        if (isset($row['kelas']) && $row['kelas'] !== null) {
            $row['kelas'] = (string) $row['kelas'];
        }
        if (isset($row['grade']) && $row['grade'] !== null) {
            $row['grade'] = (int) $row['grade'];
        }
        if (isset($row['nama_wali']) && $row['nama_wali'] !== null) {
            $row['nama_wali'] = (string) $row['nama_wali'];
        }
        if (isset($row['nomor_hp_wali']) && $row['nomor_hp_wali'] !== null) {
            $row['nomor_hp_wali'] = (string) $row['nomor_hp_wali'];
        }

        return $row;
    }

    public function model(array $row)
    {
        // Check if nama_murid is present
        if (!empty($row['nama_murid'])) {
            // Check if user already exists by email
            if (User::where('email', $row['email'])->exists()) {
                return null;
            }

            // Check if student already exists by nis
            if (Student::where('nis', $row['nis'])->exists()) {
                return null;
            }

            // Find or create class
            $class = Classroom::updateOrCreate(
                ['name' => $row['kelas']],
                ['grade' => $row['grade'] ?? 10, 'homeroom_teacher_id' => null, 'room_id' => null]
            );

            // Create user
            $user = User::create([
                'full_name' => $row['nama_murid'] ?? '',
                'email' => $row['email'] ?? null,
                'phone' => $row['no_hp'] ?? null,
                'username' => $row['nis'] ?? null,
                'password_hash' => Hash::make('password'),
                'status' => 'suspended',
            ]);

            // Create student
            Student::create([
                'user_id' => $user->id,
                'nis' => $row['nis'] ?? null,
                'class_id' => $class->id,
                'guardian_name' => $row['nama_wali'] ?? null,
                'guardian_phone' => $row['nomor_hp_wali'] ?? null,
            ]);

            // Assign role student
            $role = Role::where('name', 'student')->first();
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
            'nama_murid' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string',
            'nis' => 'nullable|string|unique:students,nis',
            'kelas' => 'nullable|string',
            'grade' => 'nullable|integer',
            'nama_wali' => 'nullable|string',
            'nomor_hp_wali' => 'nullable|string',
        ];
    }
}
