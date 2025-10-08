<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('classes')->insert([
            // Class ID: 1
            [
                'name' => 'XII RPL 1',
                'grade' => 12,
                'room_id' => 1,
                'homeroom_teacher_id' => 2, // Merujuk ke Budi Setiawan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Class ID: 2
            [
                'name' => 'XI TKJ 2',
                'grade' => 11,
                'room_id' => 2,
                'homeroom_teacher_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}