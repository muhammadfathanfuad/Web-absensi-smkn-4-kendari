<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\Attendance;
use App\Services\TimeOverrideService;
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

        // Data siswa untuk welcome message
        $namaSiswa = $user->full_name ?? 'Siswa';

        $day = TimeOverrideService::dayOfWeek();

        $timetables = collect();
        if ($classId) {
            $rawTimetables = Timetable::with(['classSubject.subject', 'classSubject.teacher.user', 'classSubject.class'])
                ->whereHas('classSubject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->where('day_of_week', $day)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get();

            // Group by class_subject_id to merge consecutive time slots for the same subject in the same class
            $grouped = $rawTimetables->groupBy('class_subject_id');

            foreach ($grouped as $classSubjectId => $timetableGroup) {
                $firstTimetable = $timetableGroup->first();
                $sorted = $timetableGroup->sortBy('start_time');
                
                // Get earliest start time and latest end time
                $earliestStart = $sorted->first()->start_time;
                $latestEnd = $sorted->last()->end_time;
                
                $timetables->push([
                    'id' => $firstTimetable->id,
                    'class_subject_id' => $firstTimetable->class_subject_id,
                    'day_of_week' => $firstTimetable->day_of_week,
                    'start_time' => $earliestStart,
                    'end_time' => $latestEnd,
                    'subject' => $firstTimetable->classSubject->subject->name ?? 'N/A',
                    'teacher_name' => $firstTimetable->classSubject->teacher->user->full_name ?? 'N/A',
                    'class_name' => $firstTimetable->classSubject->class->grade . ' - ' . $firstTimetable->classSubject->class->name,
                    'type' => $firstTimetable->type ?? 'teori',
                ]);
            }
        }

        // Attendance summary counts for the logged-in student
        $hadirCount = Attendance::where('student_id', $user->id)->where('status', 'H')->count();
        $izinCount = Attendance::where('student_id', $user->id)->where('status', 'I')->count();
        $sakitCount = Attendance::where('student_id', $user->id)->where('status', 'S')->count();
        $alpaCount = Attendance::where('student_id', $user->id)->where('status', 'A')->count();

        // Calculate daily winrate for the last 7 days
        $winrateData = [];
        $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Get total expected sessions for this day
            $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek; // Convert Sunday from 0 to 7
            $expectedSessions = 0;
            
            if ($classId) {
                $expectedSessions = Timetable::whereHas('classSubject', function($query) use ($classId) {
                        $query->where('class_id', $classId);
                    })
                    ->where('day_of_week', $dayOfWeek)
                    ->where('is_active', true)
                    ->count();
            }
            
            // Get actual attendance for this day
            $attendedSessions = Attendance::where('student_id', $user->id)
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->whereIn('status', ['H', 'T']) // Hadir or Terlambat
                ->count();
            
            // Calculate winrate
            $winrate = $expectedSessions > 0 ? round(($attendedSessions / $expectedSessions) * 100, 1) : 0;
            $winrateData[] = $winrate;
        }

        return view('murid.dashboard', compact('timetables', 'student', 'hadirCount', 'izinCount', 'sakitCount', 'alpaCount', 'namaSiswa', 'winrateData'));
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

        $user = Auth::user();

        // Query dasar untuk semua absensi siswa
        $query = Attendance::with(['classSession.timetable.classSubject.subject'])
            ->where('student_id', $user->id);

        // Jika ada filter tanggal, tambahkan kondisi whereBetween
        if ($from && $to) {
            $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }

        // Pagination dengan limit 15 per halaman
        $attendances = $query->orderByDesc('created_at')->paginate(15);

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