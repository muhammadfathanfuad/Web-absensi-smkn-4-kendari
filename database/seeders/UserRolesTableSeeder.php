<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRolesTableSeeder extends Seeder
{
    public function run()
    {
        // Temukan roles berdasarkan nama
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Temukan user berdasarkan username yang BENAR
        $adminUser = User::where('username', 'admin')->first();
        $teacherUser = User::where('username', 'budi.guru')->first();
        $studentUser1 = User::where('username', 'ahmad.budi')->first();
        $studentUser2 = User::where('username', 'siti.aminah')->first();

        // Hubungkan role ke user (dengan pengecekan)
        if ($adminUser && $adminRole) {
            $adminUser->roles()->attach($adminRole->id);
        }

        if ($teacherUser && $teacherRole) {
            $teacherUser->roles()->attach($teacherRole->id);
        }

        if ($studentUser1 && $studentRole) {
            $studentUser1->roles()->attach($studentRole->id);
        }
        
        if ($studentUser2 && $studentRole) {
            $studentUser2->roles()->attach($studentRole->id);
        }
    }
}