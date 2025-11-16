<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\Week;
use App\Services\TimeOverrideService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class JadwalPelajaranController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk murid.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $classId = $student ? $student->class_id : null;

        // Determine which day to show
        $viewDay = $request->input('view_day', 'today');
        if ($viewDay === 'besok') {
            $currentDay = TimeOverrideService::dayOfWeek();
            // If today is Friday (5), tomorrow is Monday (1)
            // If today is Saturday (6) or Sunday (7), tomorrow is Monday (1)
            // Otherwise, tomorrow is current day + 1
            if ($currentDay >= 5) {
                $day = 1; // Monday
                $now = TimeOverrideService::now();
                // Calculate next Monday
                $daysUntilMonday = (8 - $currentDay) % 7;
                if ($daysUntilMonday == 0) {
                    $daysUntilMonday = 7; // If today is Monday, next Monday is in 7 days
                }
                $viewDate = $now->copy()->addDays($daysUntilMonday);
            } else {
                $day = $currentDay + 1;
                $viewDate = TimeOverrideService::now()->addDay();
            }
        } else {
            $day = TimeOverrideService::dayOfWeek(); // 1 = Monday ... 7 = Sunday
            $viewDate = TimeOverrideService::now();
        }

        $timetables = collect();
        $allTimetables = collect();
        
        if ($classId) {
            // Determine week type (ganjil/genap) based on view date
            $currentWeekType = $this->getWeekTypeForDate($viewDate);
            
            // Get timetables for the selected day
            $rawTimetables = Timetable::with(['classSubject.subject', 'classSubject.teacher.user', 'classSubject.class'])
                ->whereHas('classSubject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->where('day_of_week', $day)
                ->orderBy('start_time')
                ->get();

            // Filter timetables based on week type and group type
            $filteredTimetables = $this->filterTimetablesByWeekType($rawTimetables, $currentWeekType);

            // Group and merge consecutive times like in admin timetable
            $timetables = $this->groupAndMergeTimetables($filteredTimetables);

            // Get all timetables for the student's class
            $rawAllTimetables = Timetable::with(['classSubject.subject', 'classSubject.teacher.user', 'classSubject.class'])
                ->whereHas('classSubject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            // Filter by week if requested
            $weekFilter = $request->input('week_filter', 'all');
            if ($weekFilter !== 'all') {
                $rawAllTimetables = $rawAllTimetables->filter(function($timetable) use ($weekFilter) {
                    // If no week_alternation, include it (for class X)
                    if (empty($timetable->week_alternation)) {
                        return true;
                    }
                    // Filter by week_alternation
                    return $timetable->week_alternation === $weekFilter;
                });
            }

            // Group and merge all timetables
            $allTimetables = $this->groupAndMergeAllTimetables($rawAllTimetables);
        }

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Check if request is for all timetables with week filter
            if ($request->has('week_filter')) {
                // Return all timetables filtered by week
                $allTimetablesData = $allTimetables->map(function($tt) {
                    $days = [
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        7 => 'Minggu'
                    ];
                    
                    // Format time to HH:mm
                    $startTime = $tt->start_time;
                    $endTime = $tt->end_time;
                    if ($startTime instanceof \DateTime || is_string($startTime)) {
                        $startTime = \Carbon\Carbon::parse($startTime)->format('H:i');
                    }
                    if ($endTime instanceof \DateTime || is_string($endTime)) {
                        $endTime = \Carbon\Carbon::parse($endTime)->format('H:i');
                    }
                    
                    return [
                        'day_of_week' => $tt->day_of_week,
                        'day_name' => $days[$tt->day_of_week] ?? 'Unknown',
                        'subject_name' => optional($tt->classSubject->subject)->name ?? '—',
                        'subject_code' => optional($tt->classSubject->subject)->code ?? null,
                        'class_name' => optional($tt->classSubject->class)->name ?? '—',
                        'location_type' => $tt->location_type ?? null,
                        'type' => $tt->type ?? 'teori',
                        'teacher_name' => optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—',
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];
                })->values();

                return response()->json([
                    'success' => true,
                    'allTimetables' => $allTimetablesData,
                    'timetables' => $allTimetablesData // Also include as timetables for backward compatibility
                ]);
            }
            
            // For today's timetable (view_day = today or not set)
            if ($viewDay !== 'besok') {
                $timetablesData = $timetables->map(function($tt) {
                    // Format time to HH:mm
                    $startTime = $tt->start_time;
                    $endTime = $tt->end_time;
                    if ($startTime instanceof \DateTime || is_string($startTime)) {
                        $startTime = \Carbon\Carbon::parse($startTime)->format('H:i');
                    }
                    if ($endTime instanceof \DateTime || is_string($endTime)) {
                        $endTime = \Carbon\Carbon::parse($endTime)->format('H:i');
                    }
                    
                    return [
                        'subject_name' => optional($tt->classSubject->subject)->name ?? '—',
                        'subject_code' => optional($tt->classSubject->subject)->code ?? null,
                        'class_name' => optional($tt->classSubject->class)->name ?? '—',
                        'location_type' => $tt->location_type ?? null,
                        'type' => $tt->type ?? 'teori',
                        'teacher_name' => optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—',
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];
                })->values();

                $days = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu'
                ];

                return response()->json([
                    'success' => true,
                    'timetables' => $timetablesData,
                    'dayName' => 'Hari Ini',
                    'dateText' => TimeOverrideService::now()->translatedFormat('l, j F Y')
                ]);
            } else {
                // For tomorrow's timetable
                $timetablesData = $timetables->map(function($tt) {
                    // Format time to HH:mm
                    $startTime = $tt->start_time;
                    $endTime = $tt->end_time;
                    if ($startTime instanceof \DateTime || is_string($startTime)) {
                        $startTime = \Carbon\Carbon::parse($startTime)->format('H:i');
                    }
                    if ($endTime instanceof \DateTime || is_string($endTime)) {
                        $endTime = \Carbon\Carbon::parse($endTime)->format('H:i');
                    }
                    
                    return [
                        'subject_name' => optional($tt->classSubject->subject)->name ?? '—',
                        'subject_code' => optional($tt->classSubject->subject)->code ?? null,
                        'class_name' => optional($tt->classSubject->class)->name ?? '—',
                        'location_type' => $tt->location_type ?? null,
                        'type' => $tt->type ?? 'teori',
                        'teacher_name' => optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—',
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];
                })->values();

                $days = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu'
                ];

                return response()->json([
                    'success' => true,
                    'timetables' => $timetablesData,
                    'dayName' => 'Besok',
                    'dateText' => $viewDate->translatedFormat('l, j F Y')
                ]);
            }
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
     * Get current week type (ganjil/genap) based on current date
     */
    private function getCurrentWeekType()
    {
        return $this->getWeekTypeForDate(TimeOverrideService::now());
    }

    /**
     * Get week type (ganjil/genap) for a specific date
     */
    private function getWeekTypeForDate($date)
    {
        $dateString = $date instanceof \Carbon\Carbon ? $date->toDateString() : $date;
        
        // Get the week from the database for the given date
        $currentWeek = Week::where('start_date', '<=', $dateString)
            ->where('end_date', '>=', $dateString)
            ->first();
        
        if ($currentWeek) {
            return $currentWeek->week_type; // 'ganjil' or 'genap'
        }
        
        // Fallback: determine based on week number if no week found in database
        $dateCarbon = $date instanceof \Carbon\Carbon ? $date : Carbon::parse($date);
        $weekNumber = $dateCarbon->week;
        return ($weekNumber % 2 == 1) ? 'ganjil' : 'genap';
    }

    /**
     * Filter timetables based on week type and group type
     * Logic:
     * - Minggu ganjil: Kelompok A = Lab, Kelompok B = Teori
     * - Minggu genap: Kelompok A = Teori, Kelompok B = Lab
     * - For non-XI classes (no group_type), show all timetables
     */
    private function filterTimetablesByWeekType($timetables, $weekType)
    {
        return $timetables->filter(function($timetable) use ($weekType) {
            // If no group_type (class X), show all timetables
            if (empty($timetable->group_type)) {
                return true;
            }
            
            // If no week_alternation, show all timetables
            if (empty($timetable->week_alternation)) {
                return true;
            }
            
            // Filter based on week type and group type
            $groupType = $timetable->group_type;
            $locationType = $timetable->location_type;
            $weekAlternation = $timetable->week_alternation;
            
            // Only show timetables that match the current week type
            if ($weekAlternation !== $weekType) {
                return false;
            }
            
            // Apply group type and location type logic
            if ($weekType === 'ganjil') {
                // Minggu ganjil: Kelompok A = Lab, Kelompok B = Teori
                if ($groupType === 'A' && $locationType === 'lab') {
                    return true;
                }
                if ($groupType === 'B' && $locationType === 'theory') {
                    return true;
                }
            } else {
                // Minggu genap: Kelompok A = Teori, Kelompok B = Lab
                if ($groupType === 'A' && $locationType === 'theory') {
                    return true;
                }
                if ($groupType === 'B' && $locationType === 'lab') {
                    return true;
                }
            }
            
            return false;
        });
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

    /**
     * Export jadwal pelajaran to PDF
     */
    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            $classId = $student ? $student->class_id : null;

            if (!$classId) {
                throw new \Exception('Siswa tidak memiliki kelas yang terdaftar.');
            }

            // Get all timetables for the student's class
            $rawAllTimetables = Timetable::with(['classSubject.subject', 'classSubject.teacher.user', 'classSubject.class'])
                ->whereHas('classSubject', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            // Filter by week if requested
            $weekFilter = $request->input('week_filter', 'all');
            if ($weekFilter !== 'all') {
                $rawAllTimetables = $rawAllTimetables->filter(function($timetable) use ($weekFilter) {
                    // If no week_alternation, include it (for class X)
                    if (empty($timetable->week_alternation)) {
                        return true;
                    }
                    // Filter by week_alternation
                    return $timetable->week_alternation === $weekFilter;
                });
            }

            // Group and merge all timetables
            $allTimetables = $this->groupAndMergeAllTimetables($rawAllTimetables);

            // Prepare data for PDF
            $days = [
                1 => 'Senin',
                2 => 'Selasa', 
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            $pdfData = [];
            foreach ($allTimetables as $index => $tt) {
                $dayName = $days[$tt->day_of_week] ?? 'Unknown';
                
                // Format jenis kelas: use location_type if available, otherwise use type
                // location_type: 'lab' -> 'Lab', 'theory' -> 'Teori'
                // type: 'praktik' -> 'Praktik', 'teori' -> 'Teori'
                $locationType = $tt->location_type ?? null;
                $type = $tt->type ?? 'teori';
                
                if ($locationType === 'lab') {
                    $typeDisplay = 'Lab';
                } elseif ($locationType === 'theory') {
                    $typeDisplay = 'Teori';
                } elseif ($type === 'praktik' || $type === 'Praktik') {
                    $typeDisplay = 'Praktik';
                } else {
                    $typeDisplay = 'Teori';
                }

                $pdfData[] = [
                    'hari' => $dayName,
                    'mapel' => optional($tt->classSubject->subject)->name ?? '—',
                    'kelas' => optional($tt->classSubject->class)->name ?? '—',
                    'jenis_kelas' => $typeDisplay,
                    'guru' => optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—',
                    'jam' => \Carbon\Carbon::parse($tt->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($tt->end_time)->format('H:i'),
                ];
            }

            $studentName = $user->full_name ?? 'Siswa';
            $className = optional($student->classroom)->name ?? 'N/A';
            $classGrade = optional($student->classroom)->grade ?? 'N/A';

            // Prepare filter info for PDF
            $filterInfo = [];
            if ($weekFilter !== 'all') {
                $filterInfo[] = 'Minggu: ' . ucfirst($weekFilter);
            }

            $filename = 'jadwal_pelajaran_' . date('YmdHis') . '.pdf';

            $pdf = Pdf::loadView('murid.jadwal-pelajaran-pdf', [
                'jadwals' => $pdfData,
                'studentName' => $studentName,
                'className' => $className,
                'classGrade' => $classGrade,
                'filterInfo' => $filterInfo,
            ]);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Export student timetable error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengexport data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}