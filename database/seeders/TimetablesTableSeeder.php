<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimetablesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('timetables')->insert([
            [
                'term_id' => 1, // 2025/2026 – Ganjil
                'class_id' => 1, // X IPA 1
                'subject_id' => 1, // Matematika
                'teacher_id' => 2, // Gantilah dengan ID yang valid
                'day_of_week' => 1, // Senin
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'room_id' => null, // Asumsi ruang kelas tidak wajib
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'term_id' => 1, // 2025/2026 – Ganjil
                'class_id' => 2, // X IPA 2
                'subject_id' => 3, // Fisika
                'teacher_id' => 2, // Gantilah dengan ID yang valid
                'day_of_week' => 2, // Selasa
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'room_id' => null, // Asumsi ruang kelas tidak wajib
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
