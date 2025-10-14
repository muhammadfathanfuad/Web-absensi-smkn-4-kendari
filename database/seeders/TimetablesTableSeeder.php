<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Term;

class TimetablesTableSeeder extends Seeder
{
    public function run()
    {
        $term = Term::where('is_active', true)->latest()->first();
        if (!$term) {
            return; // Skip if no active term
        }

        DB::table('timetables')->insert([
            [
                'term_id' => $term->id,
                'class_subject_id' => 1,
                'day_of_week' => 6, // Monday
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'type' => 'teori',
            ],
            [
                'term_id' => $term->id,
                'class_subject_id' => 2,
                'day_of_week' => 2, // Tuesday
                'start_time' => '10:00:00',
                'end_time' => '12:40:00',
                'type' => 'praktik',
            ],
            [
                'term_id' => $term->id,
                'class_subject_id' => 3,
                'day_of_week' => 3, // Wednesday
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'type' => 'teori',
            ],
        ]);
    }
}
