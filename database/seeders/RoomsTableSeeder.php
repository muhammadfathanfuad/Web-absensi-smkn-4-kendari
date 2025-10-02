<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('rooms')->insert([
            [
                'name' => 'Ruang 101',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Ruang 102',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Ruang 103',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Ruang 104',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Ruang 105',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
