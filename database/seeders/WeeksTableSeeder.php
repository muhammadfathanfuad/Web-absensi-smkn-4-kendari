<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeeksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Week::create([
            'term_id' => 1,
            'start_date' => '2025-07-21',
            'end_date' => '2025-07-25',
            'week_type' => 'ganjil',
        ]);

        \App\Models\Week::create([
            'term_id' => 1,
            'start_date' => '2025-07-28',
            'end_date' => '2025-08-01',
            'week_type' => 'genap',
        ]);

        // Add more weeks as needed
    }
}
