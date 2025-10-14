<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomsTableSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            'Ruang 101',
            'Ruang 102',
            'Ruang 103',
            'Ruang 104',
            'Ruang 105',
            'Ruang 106',
            'Ruang 107',
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->updateOrInsert(
                ['name' => $room],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
