<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('students')->insert([
            [
                'user_id' => 3, // Merujuk ke Ahmad Budi
                'nis' => '124510190',
                'class_id' => 1, // Masuk ke kelas XII RPL 1
            ],
            [
                'user_id' => 4, // Merujuk ke Siti Aminah
                'nis' => '12346',
                'class_id' => 2, // Masuk ke kelas XI TKJ 2
            ],
        ]);
    }
}