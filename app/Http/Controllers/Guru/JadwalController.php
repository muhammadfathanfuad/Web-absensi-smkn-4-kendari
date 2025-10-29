<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\TimeOverrideService;

class JadwalController extends Controller
{
    public function index()
    {
        // --- PERUBAHAN UTAMA DI SINI ---
        // Ambil teacher_id langsung dari ID user yang login, sama seperti di DashboardController
        $teacherId = Auth::user()->id;
        
        $today = TimeOverrideService::now();
        $dayOfWeek = TimeOverrideService::dayOfWeek();

        // --- Ambil Jadwal untuk Hari Ini (Logika ini sudah benar) ---
        $rawJadwalHariIni = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // Group by class_subject_id to merge duplicate subjects in same class
        $grouped = $rawJadwalHariIni->groupBy(function ($item) {
            return $item->class_subject_id;
        });

        $jadwalHariIni = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Get earliest start time and latest end time
            $earliestStart = $sortedGroup->first()->start_time;
            $latestEnd = $sortedGroup->last()->end_time;

            // Create merged entry using first item as base
            $firstJadwal = $sortedGroup->first();
            
            // Get actual student count from database
            $jumlahMurid = \App\Models\Student::where('class_id', $firstJadwal->classSubject->class_id)->count();
            
            $jadwalHariIni->push((object)[
                'id' => $firstJadwal->id,
                'start_time' => $earliestStart,
                'end_time' => $latestEnd,
                'classSubject' => $firstJadwal->classSubject,
                'jumlah_murid' => $jumlahMurid
            ]);
        }

        // --- Ambil Semua Jadwal Semester Ini dengan penggabungan ---
        $rawSemuaJadwal = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Group by day_of_week and class_subject_id to merge duplicate subjects
        $groupedSemester = $rawSemuaJadwal->groupBy(function ($item) {
            return $item->day_of_week . '-' . $item->class_subject_id;
        });

        $semuaJadwal = collect();

        foreach ($groupedSemester as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Get earliest start time and latest end time
            $earliestStart = $sortedGroup->first()->start_time;
            $latestEnd = $sortedGroup->last()->end_time;

            // Create merged entry using first item as base
            $firstJadwal = $sortedGroup->first();
            
            // Get actual student count from database
            $jumlahMurid = \App\Models\Student::where('class_id', $firstJadwal->classSubject->class_id)->count();
            
            $semuaJadwal->push((object)[
                'id' => $firstJadwal->id,
                'day_of_week' => $firstJadwal->day_of_week,
                'start_time' => $earliestStart,
                'end_time' => $latestEnd,
                'classSubject' => $firstJadwal->classSubject,
                'jumlah_murid' => $jumlahMurid
            ]);
        }

        // Group by day_of_week for display
        $semuaJadwal = $semuaJadwal->groupBy('day_of_week');

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return view('guru.jadwal-mengajar', compact('jadwalHariIni', 'semuaJadwal', 'days'));
    }
}