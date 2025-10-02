<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRolesTableSeeder extends Seeder
{
    public function run()
    {
        // Mengambil role yang sudah ada
        $admin = Role::where('name', 'admin')->first();
        $teacher = Role::where('name', 'teacher')->first();
        $student = Role::where('name', 'student')->first();

        // Menambahkan roles untuk user
        $adminUser = User::where('username', 'admin')->first();
        $adminUser->roles()->attach($admin->id);

        $teacherUser = User::where('username', 'guru_math')->first();
        $teacherUser->roles()->attach($teacher->id);

        $studentUser = User::where('username', 'murid_a')->first();
        $studentUser->roles()->attach($student->id);
    }
}
