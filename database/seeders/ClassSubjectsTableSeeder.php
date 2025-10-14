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

        DB::table('class_subjects')->updateOrInsert(
            ['class_id' => 1, 'subject_id' => 1, 'teacher_id' => 2],
            [
                'created_at' => $now,
                'updated_at' => $now
            ]
        );
        DB::table('class_subjects')->updateOrInsert(
            ['class_id' => 1, 'subject_id' => 2, 'teacher_id' => 2],
            [
                'created_at' => $now,
                'updated_at' => $now
            ]
        );
        DB::table('class_subjects')->updateOrInsert(
            ['class_id' => 2, 'subject_id' => 3, 'teacher_id' => 2],
            [
                'created_at' => $now,
                'updated_at' => $now
            ]
        );
    }
}
