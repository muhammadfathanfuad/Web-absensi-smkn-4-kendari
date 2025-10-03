<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassSessionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('class_sessions')->insert([
            [
                'timetable_id'      => 1, // pastikan ada
                'date'              => '2025-08-01',
                'start_time_actual' => '08:00:00',
                'end_time_actual'   => '09:30:00',
                'status'            => 'completed',
                'qr_token'          => 'sampleqrtoken12345',
                'qr_expires_at'     => '2025-08-01 09:00:00',
                'opened_by'         => 2,  // pastikan user id=2 ada
                'closed_by'         => 2,
                // 'last_activity'     => $now->timestamp,
                // 'ip_address'        => '127.0.0.1',
                // 'user_agent'        => 'seeder/1.0',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'timetable_id'      => 2,
                'date'              => '2025-08-02',
                'start_time_actual' => '10:00:00',
                'end_time_actual'   => '11:30:00',
                'status'            => 'scheduled',
                'qr_token'          => 'sampleqrtoken67890',
                'qr_expires_at'     => '2025-08-02 11:00:00',
                'opened_by'         => 2,
                'closed_by'         => 2,
                // 'last_activity'     => $now->timestamp,
                // 'ip_address'        => '127.0.0.1',
                // 'user_agent'        => 'seeder/1.0',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ]);
    }
}
