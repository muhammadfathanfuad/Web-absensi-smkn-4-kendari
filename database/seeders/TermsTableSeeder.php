<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TermsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('terms')->insert([
            [
                'name' => '2025/2026 – Ganjil',
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => '2025/2026 – Genap',
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'is_active' => false,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
