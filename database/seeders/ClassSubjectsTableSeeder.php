<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassSubjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('class_subjects')->insert([
            [
                'class_id' => 1, // X IPA 1
                'subject_id' => 1, // Matematika
                'teacher_id' => 2, // Gantilah dengan ID yang valid
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'class_id' => 1, // X IPA 1
                'subject_id' => 2, // Biologi
                'teacher_id' => 2, // Gantilah dengan ID yang valid
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'class_id' => 2, // X IPA 2
                'subject_id' => 3, // Fisika
                'teacher_id' => 2, // Gantilah dengan ID yang valid
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
