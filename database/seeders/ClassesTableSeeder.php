<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Pastikan 'homeroom_teacher_id' diambil dari data guru yang ada
        DB::table('classes')->insert([
            [
                'name' => 'X IPA 1',
                'grade' => 10,
                'homeroom_teacher_id' => 2, // Gantilah dengan ID yang valid
                'room_id' => null, // Asumsi ruang kelas tidak wajib
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'X IPA 2',
                'grade' => 10,
                'homeroom_teacher_id' => 2, // Gantilah dengan ID yang valid
                'room_id' => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
