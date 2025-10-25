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
        // Jika nama kosong, anggap baris ini tidak valid
        if (empty($row['nama'])) {
            $row['nama'] = null;
        }

        // Cast fields to string if present
        if (isset($row['nama']) && $row['nama'] !== null) {
            $row['nama'] = (string) $row['nama'];
        }
        if (isset($row['nis']) && $row['nis'] !== null) {
            $row['nis'] = (string) $row['nis'];
        }
        if (isset($row['kelas']) && $row['kelas'] !== null) {
            $row['kelas'] = (string) $row['kelas'];
        }
        if (isset($row['nama_wali']) && $row['nama_wali'] !== null) {
            $row['nama_wali'] = (string) $row['nama_wali'];
        }
        if (isset($row['telepon_wali']) && $row['telepon_wali'] !== null) {
            $row['telepon_wali'] = (string) $row['telepon_wali'];
        }

        return $row;
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

    public function model(array $row)
    {
        // Check if nama is present
        if (!empty($row['nama'])) {
            // Check if student already exists by nis
            if (Student::where('nis', $row['nis'])->exists()) {
                return null;
            }

            // Parse kelas and tingkatan from combined format "10 TKJA"
            $kelasData = $this->parseKelasTingkatan($row['kelas'] ?? '');
            $className = $kelasData['class_name'];
            $grade = $kelasData['grade'];

            // Find or create class
            $class = Classroom::updateOrCreate(
                ['name' => $className],
                ['grade' => $grade, 'homeroom_teacher_id' => null, 'room_id' => null]
            );

            // Create user (generate email from NIS if not provided)
            $email = $row['nis'] . '@student.smkn4kendari.sch.id';
            $user = User::create([
                'full_name' => $row['nama'] ?? '',
                'email' => $email,
                'phone' => null,
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
                'guardian_phone' => $row['telepon_wali'] ?? null,
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
            'nama' => 'nullable|string',
            'nis' => 'nullable|string|unique:students,nis',
            'kelas' => 'nullable|string',
            'nama_wali' => 'nullable|string',
            'telepon_wali' => 'nullable|string',
        ];
    }
}
