<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassSessionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('class_sessions')->insert([
            // Sesi untuk jadwal pertama (timetable_id 1) hari ini
            [
                'timetable_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'status' => 'completed', // Anggap sesi ini sudah selesai
                'start_time_actual' => '07:00:00',
                'end_time_actual' => '08:30:00',
                'opened_by' => 2, // Dibuka oleh guru (user_id = 2)
                'closed_by' => 2, // Ditutup oleh guru
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Sesi untuk jadwal kedua (timetable_id 2) hari ini
            [
                'timetable_id' => 2,
                'date' => Carbon::today()->toDateString(),
                'status' => 'scheduled', // Sesi ini dijadwalkan tapi belum dimulai
                'start_time_actual' => null,
                'end_time_actual' => null,
                'opened_by' => null,
                'closed_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}