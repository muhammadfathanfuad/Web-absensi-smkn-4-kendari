<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\TimeOverrideService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // --- PERUBAHAN UTAMA DI SINI ---
        // Ambil teacher_id langsung dari ID user yang login, sama seperti di DashboardController
        $teacherId = Auth::user()->id;
        
        // Determine which day to show
        $viewDay = $request->input('view_day', 'today');
        $now = TimeOverrideService::now();
        
        if ($viewDay === 'besok') {
            $currentDay = TimeOverrideService::dayOfWeek();
            // If today is Friday (5), tomorrow is Monday (1)
            // If today is Saturday (6) or Sunday (7), tomorrow is Monday (1)
            // Otherwise, tomorrow is current day + 1
            if ($currentDay >= 5) {
                $dayOfWeek = 1; // Monday
                // Calculate next Monday
                $daysUntilMonday = (8 - $currentDay) % 7;
                if ($daysUntilMonday == 0) {
                    $daysUntilMonday = 7; // If today is Monday, next Monday is in 7 days
                }
                $viewDate = $now->copy()->addDays($daysUntilMonday);
            } else {
                $dayOfWeek = $currentDay + 1;
                $viewDate = $now->copy()->addDay();
            }
        } else {
        $dayOfWeek = TimeOverrideService::dayOfWeek();
            $viewDate = $now;
        }

        // --- Ambil Jadwal untuk Hari Ini atau Besok ---
        $rawJadwalHariIni = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time', 'asc')
            ->get();

        // Optimasi: Pre-load student counts untuk semua class sekaligus (menghindari N+1)
        $classIds = $rawJadwalHariIni->pluck('classSubject.class_id')->unique()->filter();
        $studentCountsByClass = \App\Models\Student::whereIn('class_id', $classIds)
            ->selectRaw('class_id, COUNT(*) as count')
            ->groupBy('class_id')
            ->pluck('count', 'class_id');

        // Group by class_subject_id to merge duplicate subjects in same class
        $grouped = $rawJadwalHariIni->groupBy(function ($item) {
            return $item->class_subject_id;
        });

        $jadwalHariIni = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Get earliest start time and latest end time
            $earliestStart = $sortedGroup->first()->start_time;
            $latestEnd = $sortedGroup->last()->end_time;

            // Create merged entry using first item as base
            $firstJadwal = $sortedGroup->first();
            
            // Get student count from pre-loaded data (optimized)
            $jumlahMurid = $studentCountsByClass->get($firstJadwal->classSubject->class_id, 0);
            
            $jadwalHariIni->push((object)[
                'id' => $firstJadwal->id,
                'start_time' => $earliestStart,
                'end_time' => $latestEnd,
                'classSubject' => $firstJadwal->classSubject,
                'jumlah_murid' => $jumlahMurid
            ]);
        }

        // --- Ambil Semua Jadwal Semester Ini dengan penggabungan ---
        $rawSemuaJadwal = Timetable::with(['classSubject.class.room', 'classSubject.subject'])
            ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                $query->where('user_id', $teacherId);
            })
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Optimasi: Pre-load student counts untuk semua class sekaligus (menghindari N+1)
        $semuaClassIds = $rawSemuaJadwal->pluck('classSubject.class_id')->unique()->filter();
        $semuaStudentCountsByClass = \App\Models\Student::whereIn('class_id', $semuaClassIds)
            ->selectRaw('class_id, COUNT(*) as count')
            ->groupBy('class_id')
            ->pluck('count', 'class_id');

        // Group by day_of_week and class_subject_id to merge duplicate subjects
        $groupedSemester = $rawSemuaJadwal->groupBy(function ($item) {
            return $item->day_of_week . '-' . $item->class_subject_id;
        });

        $semuaJadwal = collect();

        foreach ($groupedSemester as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Get earliest start time and latest end time
            $earliestStart = $sortedGroup->first()->start_time;
            $latestEnd = $sortedGroup->last()->end_time;

            // Create merged entry using first item as base
            $firstJadwal = $sortedGroup->first();
            
            // Get student count from pre-loaded data (optimized)
            $jumlahMurid = $semuaStudentCountsByClass->get($firstJadwal->classSubject->class_id, 0);
            
            $semuaJadwal->push((object)[
                'id' => $firstJadwal->id,
                'day_of_week' => $firstJadwal->day_of_week,
                'start_time' => $earliestStart,
                'end_time' => $latestEnd,
                'classSubject' => $firstJadwal->classSubject,
                'jumlah_murid' => $jumlahMurid
            ]);
        }

        // Group by day_of_week for display
        $semuaJadwal = $semuaJadwal->groupBy('day_of_week');

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        // Prepare data for JSON response (if AJAX request)
        if ($request->expectsJson() || $request->ajax()) {
            $dayName = $days[$dayOfWeek] ?? 'Hari Ini';
            $dateText = $viewDate->translatedFormat('l, j F Y');
            
            return response()->json([
                'success' => true,
                'timetables' => $jadwalHariIni->map(function($jadwal) {
                    return [
                        'id' => $jadwal->id,
                        'start_time' => $jadwal->start_time,
                        'end_time' => $jadwal->end_time,
                        'subject_name' => $jadwal->classSubject->subject->name ?? 'N/A',
                        'class_name' => $jadwal->classSubject->class->name ?? 'N/A',
                        'class_grade' => $jadwal->classSubject->class->grade ?? null,
                        'jumlah_murid' => $jadwal->jumlah_murid ?? 0,
                    ];
                })->values(),
                'dayName' => $dayName,
                'dateText' => $dateText,
            ]);
        }

        return view('guru.jadwal-mengajar', compact('jadwalHariIni', 'semuaJadwal', 'days', 'viewDay', 'viewDate', 'dayOfWeek'));
    }

    public function export(Request $request)
    {
        try {
            Log::info('Export Jadwal Mengajar Guru request received', $request->all());

            // Validate format parameter
            $format = $request->get('format', 'pdf');
            if (!in_array($format, ['pdf'])) {
                $format = 'pdf';
            }

            $teacherId = Auth::user()->id;

            $days = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu',
            ];

            // Get all timetables for this teacher
            $rawSemuaJadwal = Timetable::with(['classSubject.class', 'classSubject.subject'])
                ->whereHas('classSubject.teacher', function($query) use ($teacherId) {
                    $query->where('user_id', $teacherId);
                })
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();

            // Group by day_of_week and class_subject_id to merge duplicate subjects
            $groupedSemester = $rawSemuaJadwal->groupBy(function ($item) {
                return $item->day_of_week . '-' . $item->class_subject_id;
            });

            $jadwals = collect();

            foreach ($groupedSemester as $group) {
                // Sort by start_time
                $sortedGroup = $group->sortBy('start_time');

                // Get earliest start time and latest end time
                $earliestStart = $sortedGroup->first()->start_time;
                $latestEnd = $sortedGroup->last()->end_time;

                // Create merged entry using first item as base
                $firstJadwal = $sortedGroup->first();
                
                // Get actual student count from database
                $jumlahMurid = \App\Models\Student::where('class_id', $firstJadwal->classSubject->class_id)->count();
                
                $jadwals->push([
                    'hari' => $days[$firstJadwal->day_of_week] ?? '-',
                    'jam' => Carbon::parse($earliestStart)->format('H:i') . ' - ' . Carbon::parse($latestEnd)->format('H:i'),
                    'mapel' => $firstJadwal->classSubject->subject->name ?? '-',
                    'kelas' => ($firstJadwal->classSubject->class->name ?? '-') . 
                               ($firstJadwal->classSubject->class->grade ? '-' . $firstJadwal->classSubject->class->grade : ''),
                    'jumlah_murid' => $jumlahMurid,
                    'durasi' => Carbon::parse($earliestStart)->diffInMinutes(Carbon::parse($latestEnd)) . ' Menit',
                ]);
            }

            // Sort by day and time
            $sortedJadwals = $jadwals->sortBy(function($item) use ($days) {
                $dayOrder = array_flip($days);
                $dayNum = $dayOrder[$item['hari']] ?? 99;
                $timeParts = explode(' - ', $item['jam']);
                $startTime = isset($timeParts[0]) ? strtotime($timeParts[0]) : 0;
                return $dayNum * 100000 + $startTime;
            })->values();

            // Get teacher info
            $teacher = Auth::user()->teacher;
            $teacherName = Auth::user()->full_name ?? 'Guru';

            // Generate filename
            $filename = 'jadwal_mengajar_' . date('Ymd') . '.pdf';

            // Generate PDF
            $pdf = Pdf::loadView('guru.jadwal-mengajar-pdf', [
                'jadwals' => $sortedJadwals,
                'teacherName' => $teacherName,
            ]);

            Log::info('Returning PDF file', ['filename' => $filename]);
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Return JSON error for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengexport data: ' . $e->getMessage()
                ], 500);
            }

            // Redirect back with error message for regular requests
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}