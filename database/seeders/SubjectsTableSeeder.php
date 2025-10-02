<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('subjects')->insert([
            [
                'code' => 'MTH101',
                'name' => 'Matematika',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => 'BIO101',
                'name' => 'Biologi',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'code' => 'PHYS101',
                'name' => 'Fisika',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
