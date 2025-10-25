<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\TimeOverrideService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacherId = $user->id;
        $today = TimeOverrideService::now();
        $dayOfWeek = TimeOverrideService::dayOfWeek();

        // Data guru untuk welcome message
        $guru = $user->teacher;
        $namaGuru = $user->full_name ?? 'Guru';

        $jadwalQuery = Timetable::with(['classSubject.class', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // Group by class_subject_id and type to merge consecutive times
        $grouped = $jadwalQuery->groupBy(function ($item) {
            return $item->class_subject_id . '-' . ($item->type ?? 'teori');
        });

        $jadwalMengajar = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Merge consecutive times
            $mergedTimes = [];
            $currentStart = null;
            $currentEnd = null;

            foreach ($sortedGroup as $jadwal) {
                if ($currentStart === null) {
                    $currentStart = $jadwal->start_time;
                    $currentEnd = $jadwal->end_time;
                } elseif ($jadwal->start_time === $currentEnd) {
                    $currentEnd = $jadwal->end_time;
                } else {
                    $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
                    $currentStart = $jadwal->start_time;
                    $currentEnd = $jadwal->end_time;
                }
            }
            if ($currentStart !== null) {
                $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
            }

            // Create entries for each merged time
            foreach ($mergedTimes as $time) {
                $firstJadwal = $sortedGroup->first();
                $startTime = Carbon::parse($time['start']);
                $endTime = Carbon::parse($time['end']);
                $status = 'Akan Datang';

                if ($today->isBetween($startTime, $endTime)) {
                    $status = 'Berlangsung';
                } elseif ($today->isAfter($endTime)) {
                    $status = 'Selesai';
                }

                $jadwalMengajar->push([
                    'jam' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                    'mapel' => $firstJadwal->classSubject->subject->name ?? 'N/A',
                    'kelas' => $firstJadwal->classSubject->class->name ?? 'N/A',
                    'status' => $status
                ]);
            }
        }

        // Data permohonan izin siswa hari ini
        $dayName = strtolower($today->format('l'));
        
        // Get today's timetables for this teacher
        $todayTimetables = Timetable::with(['classSubject.subject', 'classSubject.class', 'classSubject.teacher'])
            ->whereHas('classSubject', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->get();

        // Get all students from today's classes
        $studentIds = collect();
        foreach ($todayTimetables as $timetable) {
            $classStudents = Student::where('class_id', $timetable->classSubject->class->id)->pluck('user_id');
            $studentIds = $studentIds->merge($classStudents);
        }

        // Get leave requests for today's students
        $leaveRequests = \App\Models\LeaveRequest::with(['student', 'processedBy'])
            ->whereIn('student_id', $studentIds->unique())
            ->where(function($query) use ($today) {
                $query->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by student and add class information
        $siswaIzin = $leaveRequests->map(function($request) use ($todayTimetables) {
            $student = $request->student;
            $classes = collect();
            
            foreach ($todayTimetables as $timetable) {
                $classStudents = Student::where('class_id', $timetable->classSubject->class->id)->pluck('user_id');
                if ($classStudents->contains($student->id)) {
                    $classes->push([
                        'class_name' => $timetable->classSubject->class->name,
                        'subject_name' => $timetable->classSubject->subject->name,
                        'time_start' => $timetable->time_start,
                        'time_end' => $timetable->time_end
                    ]);
                }
            }
            
            // Get current teacher's status for this request
            $user = Auth::user();
            $currentTeacherId = $user && $user->teacher ? $user->teacher->user_id : null;
            $teacherStatus = 'pending'; // Default
            
            if ($currentTeacherId) {
                if (in_array($currentTeacherId, $request->approved_by_teachers ?? [])) {
                    $teacherStatus = 'approved';
                } elseif (in_array($currentTeacherId, $request->rejected_by_teachers ?? [])) {
                    $teacherStatus = 'rejected';
                }
            }
            
            return [
                'id' => $request->id,
                'nama' => $student->full_name,
                'kelas' => $classes->pluck('class_name')->unique()->implode(', '),
                'keterangan' => $request->leave_type_display,
                'status' => $teacherStatus, // Use teacher's individual status
                'overall_status' => $request->overall_status ?? $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'classes' => $classes,
                'approved_by_teachers' => $request->approved_by_teachers ?? [],
                'rejected_by_teachers' => $request->rejected_by_teachers ?? []
            ];
        })->toArray();

        // statistikKehadiranData removed — not needed on dashboard per request

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

        // Hitung total menit dari jadwal (gunakan data asli, bukan yang sudah di-merge)
        $totalMinutesToday = $jadwalQuery->sum(function ($item) {
            return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
        });

        // Hitung menit yang sudah selesai (gunakan data asli, bukan yang sudah di-merge)
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

        // Statistik tambahan untuk dashboard
        $totalJadwalHariIni = $jadwalQuery->count();
        $jadwalSelesai = $jadwalQuery->filter(function ($item) use ($today) {
            return Carbon::parse($item->end_time)->isPast();
        })->count();
        $jadwalBerlangsung = $jadwalQuery->filter(function ($item) use ($today) {
            $startTime = Carbon::parse($item->start_time);
            $endTime = Carbon::parse($item->end_time);
            return $today->isBetween($startTime, $endTime);
        })->count();

        // Data untuk chart statistik mingguan
        $statistikMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = $today->copy()->subDays($i);
            $hari = $tanggal->dayOfWeek;
            
            $jamMengajarHari = Timetable::with(['classSubject.class', 'classSubject.subject'])
                ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                    $query->where('user_id', $teacherId);
                })
                ->where('day_of_week', $hari)
                ->get()
                ->sum(function ($item) {
                    return Carbon::parse($item->start_time)->diffInMinutes(Carbon::parse($item->end_time));
                });

            $statistikMingguan[] = [
                'tanggal' => $tanggal->format('d/m'),
                'jam' => round($jamMengajarHari / 60, 1)
            ];
        }

        // Data rekap kehadiran siswa hari ini
        $rekapKehadiran = \App\Models\Attendance::with(['student.user', 'classSession.timetable.classSubject.class', 'classSession.timetable.classSubject.subject'])
            ->whereDate('created_at', $today->toDateString())
            ->whereHas('classSession.timetable.classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->get()
            ->map(function ($attendance) {
                return [
                    'nama' => $attendance->student->user->full_name ?? 'N/A',
                    'kelas' => $attendance->classSession->timetable->classSubject->class->name ?? 'N/A',
                    'mapel' => $attendance->classSession->timetable->classSubject->subject->name ?? 'N/A',
                    'jam_masuk' => $attendance->check_in_time ?? '-',
                    'status' => $attendance->status
                ];
            })
            ->take(5); // Ambil 5 data terbaru

        return view('guru.dashboard', compact(
            'namaGuru', 
            'jadwalMengajar', 
            'siswaIzin', 
            'jamMengajarData',
            'totalJadwalHariIni',
            'jadwalSelesai',
            'jadwalBerlangsung',
            'statistikMingguan',
            'rekapKehadiran'
        ));
    }
}