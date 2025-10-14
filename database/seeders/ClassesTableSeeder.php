<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            [
                'name' => 'XII RPL 1',
                'grade' => 12,
                'room_name' => 'Ruang 101',
                'homeroom_teacher_id' => 2,
            ],
            [
                'name' => 'XI TKJ 2',
                'grade' => 11,
                'room_name' => 'Ruang 102',
                'homeroom_teacher_id' => 2,
            ],
            [
                'name' => 'TKJA',
                'grade' => 10,
                'room_name' => 'Ruang 101',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'TKJB',
                'grade' => 10,
                'room_name' => 'Ruang 102',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'TKJC',
                'grade' => 10,
                'room_name' => 'Ruang 103',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'RPLA',
                'grade' => 11,
                'room_name' => 'Ruang 101',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'RPLB',
                'grade' => 11,
                'room_name' => 'Ruang 102',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'RPLC',
                'grade' => 11,
                'room_name' => 'Ruang 103',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'KTA',
                'grade' => 12,
                'room_name' => 'Ruang 101',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'KTB',
                'grade' => 12,
                'room_name' => 'Ruang 102',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'KK',
                'grade' => 12,
                'room_name' => 'Ruang 103',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'DKVA',
                'grade' => 12,
                'room_name' => 'Ruang 104',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'DKVB',
                'grade' => 12,
                'room_name' => 'Ruang 105',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'BRFA',
                'grade' => 12,
                'room_name' => 'Ruang 106',
                'homeroom_teacher_id' => null,
            ],
            [
                'name' => 'BRFB',
                'grade' => 12,
                'room_name' => 'Ruang 107',
                'homeroom_teacher_id' => null,
            ],
        ];

        foreach ($classes as $class) {
            $room = DB::table('rooms')->where('name', $class['room_name'])->first();
            if (!$room) {
                // Skip if room not found
                continue;
            }
            DB::table('classes')->updateOrInsert(
                ['name' => $class['name']],
                [
                    'grade' => $class['grade'],
                    'room_id' => $room->id,
                    'homeroom_teacher_id' => $class['homeroom_teacher_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
