<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;

class JadwalPelajaranController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk murid.
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $classId = $student ? $student->class_id : null;

        $day = Carbon::now()->isoWeekday(); // 1 = Monday ... 7 = Sunday

        $timetables = collect();
        if ($classId) {
            $timetables = Timetable::with(['subject', 'teacher.user', 'classroom'])
                ->where('class_id', $classId)
                ->where('day_of_week', $day)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get();
        }

        return view('murid.jadwal-pelajaran', compact('timetables'));
    }

    /**
     * Menampilkan halaman jadwal pelajaran.
     * UBAH FUNGSI INI
     */
    public function jadwal()
    {
        // alias to index which already returns today's jadwal
        return $this->index();
    }

    /**
     * Menampilkan halaman status absensi.
     */
    public function absensi()
    {
        // Placeholder, bisa dibuatkan view-nya nanti
        return view('murid.dashboard')->with('info', 'Halaman Status Absensi sedang dalam pengembangan.');
    }

    /**
     * Menampilkan halaman pengumuman.
     */
    public function pengumuman()
    {
        // Placeholder
        return view('murid.dashboard')->with('info', 'Halaman Pengumuman sedang dalam pengembangan.');
    }
}