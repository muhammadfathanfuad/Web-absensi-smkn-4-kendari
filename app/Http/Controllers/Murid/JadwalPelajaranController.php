<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Services\TimeOverrideService;

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

        $day = TimeOverrideService::dayOfWeek(); // 1 = Monday ... 7 = Sunday

        $timetables = collect();
        $allTimetables = collect();
        
        if ($classId) {
            // Get today's timetables
            $rawTimetables = Timetable::with(['classSubject.subject', 'classSubject.teacher.user', 'classSubject.class'])
                ->whereHas('classSubject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->where('day_of_week', $day)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get();

            // Group and merge consecutive times like in admin timetable
            $timetables = $this->groupAndMergeTimetables($rawTimetables);

            // Get all timetables for the student's class
            $rawAllTimetables = Timetable::with(['classSubject.subject', 'classSubject.teacher.user', 'classSubject.class'])
                ->whereHas('classSubject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            // Group and merge all timetables
            $allTimetables = $this->groupAndMergeAllTimetables($rawAllTimetables);
        }

        return view('murid.jadwal-pelajaran', compact('timetables', 'allTimetables'));
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

    /**
     * Group and merge consecutive timetables to handle duplicates
     */
    private function groupAndMergeTimetables($timetables)
    {
        // Group by class_subject_id and type to handle duplicates
        $grouped = $timetables->groupBy(function ($item) {
            return $item->class_subject_id . '-' . ($item->type ?? 'teori');
        });

        $mergedTimetables = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Merge consecutive times
            $mergedTimes = [];
            $currentStart = null;
            $currentEnd = null;

            foreach ($sortedGroup as $timetable) {
                if ($currentStart === null) {
                    $currentStart = $timetable->start_time;
                    $currentEnd = $timetable->end_time;
                } elseif ($timetable->start_time === $currentEnd) {
                    $currentEnd = $timetable->end_time;
                } else {
                    $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
                    $currentStart = $timetable->start_time;
                    $currentEnd = $timetable->end_time;
                }
            }
            if ($currentStart !== null) {
                $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
            }

            // Create entries for each merged time
            foreach ($mergedTimes as $time) {
                $firstTimetable = $sortedGroup->first();
                
                // Create a new object with merged time data
                $mergedTimetable = clone $firstTimetable;
                $mergedTimetable->start_time = $time['start'];
                $mergedTimetable->end_time = $time['end'];
                
                $mergedTimetables->push($mergedTimetable);
            }
        }

        return $mergedTimetables;
    }

    /**
     * Group and merge all timetables for the complete schedule view
     */
    private function groupAndMergeAllTimetables($timetables)
    {
        // Group by day_of_week, class_subject_id and type
        $grouped = $timetables->groupBy(function ($item) {
            return $item->day_of_week . '-' . $item->class_subject_id . '-' . ($item->type ?? 'teori');
        });

        $mergedTimetables = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Merge consecutive times
            $mergedTimes = [];
            $currentStart = null;
            $currentEnd = null;

            foreach ($sortedGroup as $timetable) {
                if ($currentStart === null) {
                    $currentStart = $timetable->start_time;
                    $currentEnd = $timetable->end_time;
                } elseif ($timetable->start_time === $currentEnd) {
                    $currentEnd = $timetable->end_time;
                } else {
                    $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
                    $currentStart = $timetable->start_time;
                    $currentEnd = $timetable->end_time;
                }
            }
            if ($currentStart !== null) {
                $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
            }

            // Create entries for each merged time
            foreach ($mergedTimes as $time) {
                $firstTimetable = $sortedGroup->first();
                
                // Create a new object with merged time data
                $mergedTimetable = clone $firstTimetable;
                $mergedTimetable->start_time = $time['start'];
                $mergedTimetable->end_time = $time['end'];
                
                $mergedTimetables->push($mergedTimetable);
            }
        }

        return $mergedTimetables;
    }
}