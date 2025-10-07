<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Pastikan semua model ini ada dan namespace-nya benar
use App\Models\Timetable;
use App\Models\ClassSession;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Nantinya, ini akan didapat dari user yang sedang login
        // Contoh: $teacherId = auth()->user()->id;
        $teacherId = 1; 
        $today = Carbon::now();
        
        // Logika untuk hari: 0=Minggu -> 7, 1=Senin, dst.
        $dayOfWeek = $today->dayOfWeek == 0 ? 7 : $today->dayOfWeek;

        // --- Jadwal Mengajar Hari Ini ---
        $jadwalQuery = Timetable::with(['classSubject.subject', 'classSubject.classroom'])
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        $jadwalMengajar = $jadwalQuery->map(function ($item) use ($today) {
            $startTime = Carbon::parse($item->start_time);
            $endTime = Carbon::parse($item->end_time);
            $status = 'Akan Datang';

            if ($today->isBetween($startTime, $endTime)) {
                $status = 'Berlangsung';
            } elseif ($today->isAfter($endTime)) {
                $status = 'Selesai';
            }

            return [
                'jam' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                'mapel' => $item->classSubject->subject->name ?? 'N/A',
                'kelas' => $item->classSubject->classroom->name ?? 'N/A', // Asumsi relasi 'classroom' di model ClassSubject
                'status' => $status
            ];
        });

        // --- List Siswa Izin Hari Ini (FIXED) ---
        // Menggunakan kolom `created_at` yang benar
        $siswaIzin = ClassSession::with(['student.classroom'])
            ->whereDate('created_at', $today->toDateString()) // PERBAIKAN DI SINI
            ->whereIn('status', ['S', 'I'])
            ->get()
            ->map(function ($session) {
                // Tambahkan pengecekan untuk menghindari error jika relasi student null
                if (!$session->student) {
                    return null;
                }
                return [
                    'nama' => $session->student->name ?? 'Siswa tidak ditemukan',
                    'kelas' => $session->student->classroom->name ?? 'N/A',
                    'keterangan' => $session->status == 'S' ? 'Sakit' : 'Izin'
                ];
            })->filter(); // Menghapus item null dari koleksi

        // --- Jam Mengajar Hari Ini ---
        $totalMinutesToday = $jadwalQuery->sum(function ($item) {
            return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
        });
        $totalHoursToday = $totalMinutesToday > 0 ? $totalMinutesToday / 60 : 0;

        $completedMinutes = $jadwalQuery->filter(function ($item) use ($today) {
            return Carbon::parse($item->end_time)->isPast();
        })->sum(function ($item) {
            return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
        });
        $completedHours = $completedMinutes > 0 ? $completedMinutes / 60 : 0;
        
        $jamMengajarData = [
            'persentase' => $totalMinutesToday > 0 ? round(($completedMinutes / $totalMinutesToday) * 100) : 0,
            'label' => round($completedHours) . ' dari ' . round($totalHoursToday) . ' Jam'
        ];

        // --- Riwayat Mengajar Bulan Ini ---
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $schedules = Timetable::where('teacher_id', $teacherId)->get();
        $weeklyHours = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $currentDayOfWeek = $date->dayOfWeek == 0 ? 7 : $date->dayOfWeek;
            $weekOfMonth = $date->weekOfMonth;

            foreach ($schedules as $schedule) {
                if ($schedule->day_of_week == $currentDayOfWeek) {
                    $duration = Carbon::parse($schedule->start_time)->diffInHours(Carbon::parse($schedule->end_time));
                    if (isset($weeklyHours[$weekOfMonth])) {
                        $weeklyHours[$weekOfMonth] += $duration;
                    }
                }
            }
        }
        
        $riwayatMengajarData = [
            'categories' => [],
            'series' => [['name' => 'Total Jam Mengajar', 'data' => []]]
        ];
        
        foreach($weeklyHours as $weekNum => $hours) {
            if ($hours > 0) {
                $riwayatMengajarData['categories'][] = "Minggu $weekNum";
                $riwayatMengajarData['series'][0]['data'][] = $hours;
            }
        }

        // --- Statistik Kehadiran Siswa Hari Ini (FIXED) ---
        // Menggunakan kolom `created_at` yang benar
        $stats = ClassSession::whereDate('created_at', $today->toDateString()) // PERBAIKAN DI SINI
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
            
        $statistikKehadiranData = [
            'series' => [
                $stats->get('H', 0), $stats->get('S', 0), $stats->get('I', 0), $stats->get('A', 0)
            ],
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Alpha']
        ];
        
        // --- Pengumuman (Masih statis) ---
        $pengumuman = [
            ['judul' => 'Rapat Dewan Guru', 'tanggal' => '10 Oktober 2025 - 08:00', 'icon' => 'solar:megaphone-bold'],
            ['judul' => 'Kegiatan Class Meeting', 'tanggal' => '15 Desember 2025', 'icon' => 'solar:calendar-bold'],
        ];

        return view('guru.dashboard', compact(
            'jadwalMengajar', 'siswaIzin', 'jamMengajarData', 
            'riwayatMengajarData', 'statistikKehadiranData', 'pengumuman'
        ));
    }
}

