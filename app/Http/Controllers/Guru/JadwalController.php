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
        $jadwalHariIni = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // --- Ambil Semua Jadwal Semester Ini (Logika ini juga sudah benar) ---
        $semuaJadwal = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
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