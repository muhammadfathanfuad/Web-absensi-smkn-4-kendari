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
        $teacherId = Auth::user()->teacher->user_id;
        $today = TimeOverrideService::now();
        $dayOfWeek = $today->dayOfWeekIso;

        // --- 1. Ambil Jadwal untuk Hari Ini ---
        $jadwalHariIni = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // --- 2. Ambil Semua Jadwal Semester Ini ---
        $semuaJadwal = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('day_of_week');

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