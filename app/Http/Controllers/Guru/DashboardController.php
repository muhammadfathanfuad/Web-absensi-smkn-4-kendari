<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacherId = Auth::user()->id;
        $today = Carbon::now();
        $dayOfWeek = $today->dayOfWeekIso;

        $jadwalQuery = Timetable::with(['classroom', 'subject'])
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
                'mapel' => $item->subject->name ?? 'N/A',
                'kelas' => $item->classroom->name ?? 'N/A',
                'status' => $status
            ];
        });

        $siswaIzin = [
            ['nama' => 'Ahmad Budi (Contoh)', 'kelas' => 'XII RPL 1', 'keterangan' => 'Izin'],
            ['nama' => 'Siti Aminah (Contoh)', 'kelas' => 'XI TKJ 2', 'keterangan' => 'Sakit'],
        ];

        $statistikKehadiranData = [
            'series' => [150, 2, 1, 0],
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Alpha']
        ];

        // --- PERUBAHAN DI BAGIAN INI ---

        // Fungsi kecil untuk mengubah menit menjadi format "X Jam Y Menit"
        $formatMinutesToHoursAndMinutes = function ($totalMinutes) {
            if ($totalMinutes <= 0) {
                return '0 Jam';
            }
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            $hourText = $hours > 0 ? $hours . ' Jam' : '';
            $minuteText = $minutes > 0 ? $minutes . ' Menit' : '';

            return trim($hourText . ' ' . $minuteText);
        };

        // Hitung total menit dari jadwal
        $totalMinutesToday = $jadwalQuery->sum(function ($item) {
            return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
        });

        // Hitung menit yang sudah selesai
        $completedMinutes = $jadwalQuery->filter(function ($item) use ($today) {
            return Carbon::parse($item->end_time)->isPast();
        })->sum(function ($item) {
            return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
        });

        // Buat data untuk view menggunakan fungsi format yang baru
        $jamMengajarData = [
            'persentase' => $totalMinutesToday > 0 ? round(($completedMinutes / $totalMinutesToday) * 100) : 0,
            'label' => $formatMinutesToHoursAndMinutes($completedMinutes) . ' dari ' . $formatMinutesToHoursAndMinutes($totalMinutesToday)
        ];

        // --- AKHIR PERUBAHAN ---

        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $schedules = Timetable::where('teacher_id', $teacherId)->get();
        $weeklyHours = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $currentDayOfWeek = $date->dayOfWeekIso;
            $weekOfMonth = $date->weekOfMonth;

            foreach ($schedules as $schedule) {
                if ($schedule->day_of_week == $currentDayOfWeek) {
                    $duration = Carbon::parse($schedule->start_time)->diffInMinutes(Carbon::parse($schedule->end_time));
                    if (isset($weeklyHours[$weekOfMonth])) {
                        // Simpan dalam menit agar lebih akurat
                        $weeklyHours[$weekOfMonth] += $duration;
                    }
                }
            }
        }
        
        $riwayatMengajarData = [
            'categories' => [],
            'series' => [['name' => 'Total Jam Mengajar', 'data' => []]]
        ];
        
        foreach($weeklyHours as $weekNum => $totalMinutesInWeek) {
            if ($totalMinutesInWeek > 0) {
                $riwayatMengajarData['categories'][] = "Minggu $weekNum";
                // Ubah kembali ke jam untuk ditampilkan di chart
                $riwayatMengajarData['series'][0]['data'][] = round($totalMinutesInWeek / 60, 1);
            }
        }
        
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