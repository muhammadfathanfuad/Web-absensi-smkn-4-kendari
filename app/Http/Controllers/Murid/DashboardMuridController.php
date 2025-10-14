<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\Attendance;
// Jika nanti Anda memerlukan model, tambahkan di sini. Contoh:
// use App\Models\Jadwal;
// use App\Models\Pengumuman;

class DashboardMuridController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk murid.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $classId = $student ? $student->class_id : null;

        $day = Carbon::now()->isoWeekday();

        $timetables = collect();
        if ($classId) {
            $timetables = Timetable::with(['subject', 'teacher.user'])
                ->where('class_id', $classId)
                ->where('day_of_week', $day)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get();
        }

        // Attendance summary counts for the logged-in student
        $hadirCount = Attendance::where('student_id', $user->id)->where('status', 'H')->count();
        $izinCount = Attendance::where('student_id', $user->id)->where('status', 'I')->count();
        $sakitCount = Attendance::where('student_id', $user->id)->where('status', 'S')->count();
        $alpaCount = Attendance::where('student_id', $user->id)->where('status', 'A')->count();

        return view('murid.dashboard', compact('timetables', 'student', 'hadirCount', 'izinCount', 'sakitCount', 'alpaCount'));
    }

    /**
     * Menampilkan halaman jadwal pelajaran.
     * (Belum dibuat, ini hanya contoh)
     *
     * @return \Illuminate\View\View
     */
    public function jadwal()
    {
        // Logika untuk mengambil data jadwal pelajaran murid
        // $jadwal = Jadwal::where('kelas_id', $murid->kelas_id)->get();
        
        // return view('murid.jadwal-pelajaran', compact('jadwal'));
        
        // Karena view belum dibuat, kita arahkan ke dashboard saja sebagai placeholder
        return view('murid.dashboard')->with('info', 'Halaman Jadwal Pelajaran sedang dalam pengembangan.');
    }

    /**
     * Menampilkan halaman status absensi.
     * (Belum dibuat, ini hanya contoh)
     *
     * @return \Illuminate\View\View
     */
    public function absensi()
    {
        // Ambil rentang tanggal dari query string (format YYYY-MM-DD)
        $from = request()->query('from');
        $to = request()->query('to');

        // Default: 30 hari terakhir
        if (! $from || ! $to) {
            $to = Carbon::now()->endOfDay()->toDateString();
            $from = Carbon::now()->subDays(30)->startOfDay()->toDateString();
        }

        $user = Auth::user();

        $attendances = Attendance::with(['classSession.timetable.subject'])
            ->where('student_id', $user->id)
            ->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->orderByDesc('created_at')
            ->get();

        return view('murid.riwayat-absensi', compact('attendances', 'from', 'to'));
    }

    /**
     * Menampilkan halaman pengumuman.
     * (Belum dibuat, ini hanya contoh)
     *
     * @return \Illuminate\View\View
     */
     public function pengumuman()
    {
        // Pengumuman feature telah dihapus — arahkan kembali ke dashboard dengan pesan.
        return view('murid.dashboard')->with('info', 'Halaman Pengumuman telah dihapus.');
    }

    /**
     * Menampilkan halaman scanner QR untuk murid.
     */
    public function qr()
    {
        return view('murid.qr-absensi');
    }

}