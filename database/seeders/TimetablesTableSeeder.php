<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimetablesTableSeeder extends Seeder
{
    public function run()
    {
        // Hari ini adalah Selasa, jadi day_of_week = 2
        $todayDayOfWeek = 6;

        DB::table('timetables')->insert([
            [
                'term_id' => 1,
                'class_id' => 1,      // Kelas XII RPL 1
                'subject_id' => 3,    // Pemrograman Web
                'teacher_id' => 2,    // Budi Guru
                'day_of_week' => $todayDayOfWeek,
                'start_time' => '23:00:00',
                'end_time' => '23:59:00',
            ],
            [
                'term_id' => 1,
                'class_id' => 2,      // Kelas XI TKJ 2
                'subject_id' => 1,    // Matematika
                'teacher_id' => 2,    // Budi Guru
                'day_of_week' => $todayDayOfWeek,
                'start_time' => '08:00:00',
                'end_time' => '10:59:00',
            ],

            [
                'term_id' => 1,
                'class_id' => 2,      // Kelas XI TKJ 2
                'subject_id' => 3,    // Bahasa Indonesia
                'teacher_id' => 2,    // Budi Guru
                'day_of_week' => 3,   // Hari Rabu
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
            ],
        ]);
    }
}