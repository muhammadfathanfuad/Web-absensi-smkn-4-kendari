<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $teacherId = 2;
        $today = Carbon::now();
        $dayOfWeek = $today->dayOfWeekIso;

        // --- 1. Ambil Jadwal untuk Hari Ini ---
        // PERUBAHAN DI SINI: kita muat relasi classroom beserta room di dalamnya
        $jadwalHariIni = Timetable::with(['classroom.room', 'subject'])
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // --- 2. Ambil Semua Jadwal Semester Ini ---
        // PERUBAHAN DI SINI JUGA
        $semuaJadwal = Timetable::with(['classroom.room', 'subject'])
            ->where('teacher_id', $teacherId)
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