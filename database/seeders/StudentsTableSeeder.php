<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        Student::create([
            'user_id' => 3, // ID dari user murid
            'nis' => 'S12345',
            'class_id' => 2, // ID kelas
            'guardian_name' => 'Bapak A',
            'guardian_phone' => '085678901234'
        ]);
    }
}
