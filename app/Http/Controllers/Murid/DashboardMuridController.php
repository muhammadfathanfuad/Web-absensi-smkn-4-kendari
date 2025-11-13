<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\ClassSubject;
use App\Services\TimeOverrideService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
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
        $subjectId = request()->query('subject_id');

        $user = Auth::user();

        // Query dasar untuk semua absensi siswa
        $query = Attendance::with(['classSession.timetable.classSubject.subject'])
            ->where('student_id', $user->id);

        // Jika ada filter tanggal, tambahkan kondisi whereBetween
        if ($from && $to) {
            $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }

        // Jika ada filter mata pelajaran, tambahkan kondisi
        if ($subjectId) {
            $query->whereHas('classSession.timetable.classSubject', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        // Pagination dengan limit 15 per halaman
        $attendances = $query->orderByDesc('created_at')->paginate(15);

        // Get all subjects for filter dropdown (subjects from student's timetable/schedule)
        $student = Student::where('user_id', $user->id)->first();
        $classId = $student ? $student->class_id : null;
        
        $subjects = collect();
        if ($classId) {
            // Get unique subject IDs from student's timetables using join for better performance
            $subjectIds = Timetable::join('class_subjects', 'timetables.class_subject_id', '=', 'class_subjects.id')
                ->where('class_subjects.class_id', $classId)
                ->distinct()
                ->pluck('class_subjects.subject_id')
                ->filter()
                ->toArray();
            
            // Get subjects by IDs
            if (!empty($subjectIds)) {
                $subjects = Subject::whereIn('id', $subjectIds)
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('murid.riwayat-absensi', compact('attendances', 'from', 'to', 'subjects', 'subjectId'));
    }

    /**
     * Export attendance history to PDF
     */
    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Data siswa tidak ditemukan.'], 404);
                }
                return redirect()->route('murid.absensi')->with('error', 'Data siswa tidak ditemukan.');
            }

            // Get filter parameters
            $from = $request->input('from');
            $to = $request->input('to');
            $subjectId = $request->input('subject_id');

            // Query dasar untuk semua absensi siswa
            $query = Attendance::with(['classSession.timetable.classSubject.subject', 'classSession.timetable.classSubject.class'])
                ->where('student_id', $user->id);

            // Apply filters
            if ($from && $to) {
                $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            }

            if ($subjectId) {
                $query->whereHas('classSession.timetable.classSubject', function($q) use ($subjectId) {
                    $q->where('subject_id', $subjectId);
                });
            }

            // Get all attendances
            $attendances = $query->orderByDesc('created_at')->get();

            // Prepare PDF data
            $pdfData = [];
            foreach ($attendances as $att) {
                $subject = optional(optional(optional($att->classSession)->timetable)->classSubject)->subject;
                $subjectName = $subject ? $subject->name : '—';

                // Format status
                $statusMap = [
                    'H' => 'Hadir',
                    'I' => 'Izin',
                    'S' => 'Sakit',
                    'T' => 'Terlambat',
                    'A' => 'Alpa'
                ];
                $statusText = $statusMap[$att->status] ?? $att->status;

                // Format keterangan
                $notes = '';
                if ($att->status === 'H') {
                    if ($att->check_in_time) {
                        $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i');
                        $notes = "Hadir tepat waktu (Scan: {$checkInTime})";
                    } else {
                        $notes = 'Hadir tepat waktu';
                    }
                } elseif ($att->status === 'T') {
                    $lateMinutes = abs(round($att->late_minutes ?? 0));
                    if ($lateMinutes === 0) {
                        $timeFormat = '0 menit';
                    } elseif ($lateMinutes < 60) {
                        $timeFormat = "{$lateMinutes} menit";
                    } else {
                        $hours = floor($lateMinutes / 60);
                        $remainingMinutes = $lateMinutes % 60;
                        if ($remainingMinutes === 0) {
                            $timeFormat = "{$hours} jam";
                        } else {
                            $timeFormat = "{$hours} jam {$remainingMinutes} menit";
                        }
                    }
                    if ($att->check_in_time) {
                        $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i');
                        $notes = "Terlambat {$timeFormat} (Scan: {$checkInTime})";
                    } else {
                        $notes = "Terlambat {$timeFormat}";
                    }
                } elseif ($att->status === 'A') {
                    $notes = 'Tidak hadir - tidak melakukan scan';
                } elseif ($att->status === 'I') {
                    $notes = 'Izin';
                } elseif ($att->status === 'S') {
                    $notes = 'Sakit';
                } else {
                    $notes = $att->notes ?? '-';
                }

                if ($att->check_out_time) {
                    $checkOutTime = \Carbon\Carbon::parse($att->check_out_time)->format('H:i');
                    $notes .= " (Keluar: {$checkOutTime})";
                }

                $pdfData[] = [
                    'tanggal' => $att->created_at ? $att->created_at->format('d F Y') : '—',
                    'mata_pelajaran' => $subjectName,
                    'status' => $statusText,
                    'jam_masuk' => $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '-',
                    'keterangan' => $notes,
                ];
            }

            $studentName = $user->full_name ?? 'Siswa';

            // Build filter info
            $filterInfo = [];
            if ($from && $to) {
                $filterInfo[] = 'Periode: ' . Carbon::parse($from)->format('d M Y') . ' - ' . Carbon::parse($to)->format('d M Y');
            }
            if ($subjectId) {
                $subject = Subject::find($subjectId);
                if ($subject) {
                    $filterInfo[] = 'Mata Pelajaran: ' . $subject->name;
                }
            }

            // Generate filename
            $filename = 'riwayat_absensi_' . date('YmdHis') . '.pdf';

            // Load PDF view
            $pdf = Pdf::loadView('murid.riwayat-absensi-pdf', [
                'attendances' => $pdfData,
                'studentName' => $studentName,
                'filterInfo' => $filterInfo,
            ]);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error exporting attendance history: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengexport data. Silakan coba lagi.'], 500);
            }
            return redirect()->route('murid.absensi')->with('error', 'Gagal mengexport data. Silakan coba lagi.');
        }
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