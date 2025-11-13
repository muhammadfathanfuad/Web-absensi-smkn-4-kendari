<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Timetable;
use App\Models\XiClass;
use App\Models\Term;
use App\Imports\XiTimetableImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class XiJadwalController extends Controller
{
    public function index(Request $request)
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa', 
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        $query = Timetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user', 'term'])
            ->whereHas('classSubject.class', function($q) {
                $q->where('grade', '11');
            });

        // Apply filters
        if ($request->group_type && $request->group_type !== 'all' && $request->group_type !== '') {
            $query->groupType($request->group_type);
        }

        if ($request->week_type && $request->week_type !== 'all' && $request->week_type !== '') {
            $query->weekType($request->week_type);
        }

        if ($request->location_type && $request->location_type !== 'all' && $request->location_type !== '') {
            $query->locationType($request->location_type);
        }

        if ($request->class && $request->class !== 'all' && $request->class !== '') {
            $query->whereHas('classSubject.class', function($q) use ($request) {
                $q->where('name', $request->class);
            });
        }

        if ($request->day && $request->day !== 'all' && $request->day !== '') {
            $dayNumber = array_search($request->day, $days);
            if ($dayNumber) {
                $query->where('day_of_week', $dayNumber);
            }
        }

        // Filter by term_id if provided, otherwise use active term
        if ($request->has('term_id') && $request->term_id) {
            \Log::info('Filtering Kelas XI by term_id: ' . $request->term_id);
            $query->where('term_id', $request->term_id);
        } else {
            // Default to active term if no term_id provided
            $activeTerm = Term::where('is_active', true)->latest()->first();
            if ($activeTerm) {
                $query->where('term_id', $activeTerm->id);
                \Log::info('No term_id filter applied, using active term: ' . $activeTerm->id);
            } else {
                \Log::info('No term_id filter applied and no active term found for Kelas XI');
            }
        }

        $timetables = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        \Log::info('Found ' . $timetables->count() . ' Kelas XI timetables');

        // Group timetables by day and time for display
        $groupedTimetables = $this->groupTimetablesForDisplay($timetables, $days);

        // Return array directly (not wrapped in object) to match Grid.js expectations
        return response()->json($groupedTimetables->values());
    }

    private function groupTimetablesForDisplay($timetables, $days)
    {
        // Group by day_of_week, class_subject_id, type, group_type, location_type, week_type
        // Use week_type instead of week_alternation to properly separate ganjil/genap
        $grouped = $timetables->groupBy(function ($item) {
            return $item->day_of_week . '-' . 
                   $item->class_subject_id . '-' . 
                   ($item->type ?? 'teori') . '-' . 
                   ($item->group_type ?? '') . '-' . 
                   ($item->location_type ?? '') . '-' . 
                   ($item->week_type ?? '');
        });

        $jadwals = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Merge consecutive times within each group (only if times are exactly consecutive)
            $mergedTimes = [];
            $currentStart = null;
            $currentEnd = null;

            foreach ($sortedGroup as $jadwal) {
                if ($currentStart === null) {
                    $currentStart = $jadwal->start_time;
                    $currentEnd = $jadwal->end_time;
                } else {
                    // Convert times to comparable format for proper comparison
                    $currentEndTime = is_object($currentEnd) ? $currentEnd->format('H:i:s') : (is_string($currentEnd) ? date('H:i:s', strtotime($currentEnd)) : '00:00:00');
                    $jadwalStartTime = is_object($jadwal->start_time) ? $jadwal->start_time->format('H:i:s') : (is_string($jadwal->start_time) ? date('H:i:s', strtotime($jadwal->start_time)) : '00:00:00');
                    
                    // Only merge if the next start time exactly equals the current end time
                    if ($jadwalStartTime === $currentEndTime) {
                        $currentEnd = $jadwal->end_time;
                    } else {
                        // Save current merged time and start new one
                        $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
                        $currentStart = $jadwal->start_time;
                        $currentEnd = $jadwal->end_time;
                    }
                }
            }
            if ($currentStart !== null) {
                $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
            }

            // Create entries for each time slot (merged or individual)
            foreach ($mergedTimes as $time) {
                // Find the jadwal entry that matches this time range
                $matchingJadwal = $sortedGroup->first(function($j) use ($time) {
                    $jStart = is_object($j->start_time) ? $j->start_time->format('H:i:s') : date('H:i:s', strtotime($j->start_time));
                    $tStart = is_object($time['start']) ? $time['start']->format('H:i:s') : date('H:i:s', strtotime($time['start']));
                    return $jStart === $tStart;
                });
                
                if (!$matchingJadwal) {
                    $matchingJadwal = $sortedGroup->first();
                }
                
                // Format time using the same method as class 10
                $formattedStart = $this->formatTimeForDisplay($time['start']);
                $formattedEnd = $this->formatTimeForDisplay($time['end']);
                
                // Format jenis kelas: use location_type if available, otherwise use type
                // location_type: 'lab' -> 'Lab', 'theory' -> 'Teori'
                // type: 'praktik' -> 'Praktik', 'teori' -> 'Teori'
                $locationType = $matchingJadwal->location_type ?? null;
                $type = $matchingJadwal->type ?? 'teori';
                
                if ($locationType === 'lab') {
                    $typeDisplay = 'Lab';
                } elseif ($locationType === 'theory') {
                    $typeDisplay = 'Teori';
                } elseif ($type === 'praktik' || $type === 'Praktik') {
                    $typeDisplay = 'Praktik';
                } else {
                    $typeDisplay = 'Teori';
                }
                
                $jadwals->push([
                    'id' => $matchingJadwal->id, // Use matching id for actions
                    'hari' => $days[$matchingJadwal->day_of_week] ?? '-',
                    'jam' => $formattedStart . ' - ' . $formattedEnd,
                    'kelas' => $this->formatClassName($matchingJadwal->classSubject?->class?->name ?? '-', $matchingJadwal->classSubject?->class?->grade ?? ''),
                    'mapel' => $matchingJadwal->classSubject?->subject?->name ?? '-',
                    'guru' => $matchingJadwal->classSubject?->teacher?->user?->full_name ?? '-',
                    'ruangan' => $matchingJadwal->room ?? '-',
                    'jenis' => $typeDisplay,
                    'kelompok' => $matchingJadwal->group_type_display ?? '-',
                    'lokasi' => $matchingJadwal->location_type_display ?? '-',
                    'minggu' => $matchingJadwal->week_alternation_display ?? ($matchingJadwal->week_type === 'ganjil' ? 'Ganjil' : ($matchingJadwal->week_type === 'genap' ? 'Genap' : '-')),
                ]);
            }
        }

        // Sort final results by day and time
        return $jadwals->sortBy(function($item) use ($days) {
            $dayOrder = array_flip($days);
            $dayNum = $dayOrder[$item['hari']] ?? 99;
            $timeParts = explode(' - ', $item['jam']);
            $startTime = isset($timeParts[0]) ? $this->parseTime($timeParts[0]) : 0;
            return $dayNum * 10000 + $startTime;
        })->values();
    }

    private function parseTime($timeString)
    {
        // Parse time string to minutes since midnight for comparison
        $parts = explode(':', $timeString);
        if (count($parts) >= 2) {
            return intval($parts[0]) * 60 + intval($parts[1]);
        }
        return 0;
    }

    private function formatTimeForDisplay($time)
    {
        if (!$time) {
            return '-';
        }

        // Convert to string if it's a Carbon instance
        if (is_object($time) && method_exists($time, 'format')) {
            $time = $time->format('Y-m-d H:i:s');
        }
        
        if (is_string($time)) {
            // Try different parsing methods
            $parsed = strtotime($time);
            if ($parsed !== false) {
                return date('H:i', $parsed);
            } else {
                // Fallback: try to extract time from string
                if (preg_match('/(\d{1,2}):(\d{2})/', $time, $matches)) {
                    return sprintf('%02d:%02d', $matches[1], $matches[2]);
                } elseif (preg_match('/(\d{1,2})\.(\d{2})/', $time, $matches)) {
                    return sprintf('%02d:%02d', $matches[1], $matches[2]);
                } else {
                    // Try to extract from datetime format
                    if (preg_match('/(\d{4}-\d{2}-\d{2})\s+(\d{1,2}):(\d{2})/', $time, $matches)) {
                        return sprintf('%02d:%02d', $matches[2], $matches[3]);
                    }
                }
            }
        }

        return '-';
    }

    private function formatClassName($className, $grade)
    {
        if ($className === '-' || empty($grade)) {
            return $className;
        }
        
        // Convert numeric grade to display format
        $gradeMap = ['10' => 'X', '11' => 'XI', '12' => 'XII'];
        $displayGrade = $gradeMap[$grade] ?? $grade;
        
        return $className . '-' . $displayGrade;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:10240', // 10MB max
            'group_type' => 'required|in:A,B',
            'grade' => 'required|in:X,XI,XII',
            'term_id' => 'required|exists:terms,id',
        ]);

        try {
            // Get the selected term
            $term = Term::find($request->term_id);
            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semester yang dipilih tidak ditemukan.'
                ], 400);
            }

            $import = new XiTimetableImport($request->group_type, $request->grade, $term->id);
            Excel::import($import, $request->file('file'));

            // Get the processed count and duplicate count
            $processedCount = $import->getProcessedCount();
            $duplicateCount = $import->getDuplicateCount();
            $errors = $import->getErrors();

            if ($processedCount > 0 || $duplicateCount > 0) {
                $groupText = "Kelompok {$request->group_type}";
                $gradeText = " untuk Kelas {$request->grade}";
                
                $message = "Jadwal berhasil diimport! {$processedCount} entri baru ditambahkan untuk {$groupText}{$gradeText}.";
                
                if ($duplicateCount > 0) {
                    $message .= " {$duplicateCount} entri duplikat ditemukan dan dilewati (data sudah ada di database).";
                }
                
                if (!empty($errors)) {
                    $message .= " Terdapat " . count($errors) . " error yang perlu diperhatikan.";
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'processedCount' => $processedCount,
                    'duplicateCount' => $duplicateCount,
                    'errors' => $errors
                ]);
            } else {
                if ($duplicateCount > 0) {
                    return response()->json([
                        'success' => true,
                        'message' => "Semua data sudah ada di database. {$duplicateCount} entri duplikat ditemukan dan dilewati.",
                        'processedCount' => 0,
                        'duplicateCount' => $duplicateCount,
                    'errors' => $errors
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang diproses. Periksa format file Excel dan pastikan kode guru serta kode pelajaran sudah ada di database.'
                ], 400);
                }
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error('XI Import jadwal validation error: ' . $e->getMessage());
            
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi file Excel gagal: ' . implode('; ', $errorMessages)
            ], 400);
        } catch (\Exception $e) {
            Log::error('XI Import jadwal error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengimport jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilterOptions()
    {
        $classes = XiClass::xiClasses()
            ->orderBy('name')
            ->get()
            ->pluck('name')
            ->unique()
            ->values();

        $groupTypes = ['A', 'B'];
        $weekTypes = ['ganjil', 'genap'];
        $locationTypes = ['lab', 'theory'];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return response()->json([
            'classes' => $classes,
            'groupTypes' => $groupTypes,
            'weekTypes' => $weekTypes,
            'locationTypes' => $locationTypes,
            'days' => $days
        ]);
    }

    public function getStatistics()
    {
        $totalTimetables = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->count();
        $groupA = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->groupType('A')->count();
        $groupB = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->groupType('B')->count();
        $labSessions = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->locationType('lab')->count();
        $theorySessions = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->locationType('theory')->count();

        return response()->json([
            'total' => $totalTimetables,
            'groupA' => $groupA,
            'groupB' => $groupB,
            'labSessions' => $labSessions,
            'theorySessions' => $theorySessions
        ]);
    }

    public function destroy($id)
    {
        $timetable = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->find($id);
        if (!$timetable) {
            return response()->json(['success' => false, 'message' => 'Jadwal XI tidak ditemukan.'], 404);
        }
        $timetable->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal XI berhasil dihapus.']);
    }

    public function bulkDelete(Request $request)
    {
        $idsParam = $request->input('ids');
        
        if (empty($idsParam)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada jadwal XI yang dipilih.'], 400);
        }

        $ids = is_array($idsParam) ? $idsParam : explode(',', (string)$idsParam);
        $ids = array_filter(array_map('trim', $ids), function($v) { return $v !== ''; });

        if (count($ids) === 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada jadwal XI yang dipilih.'], 400);
        }

        // Cek apakah data ada sebelum dihapus (dengan scope XI)
        $existingCount = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->whereIn('id', $ids)->count();
        
        if ($existingCount === 0) {
            return response()->json(['success' => false, 'message' => 'Jadwal XI tidak ditemukan.'], 404);
        }

        $deletedCount = Timetable::whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        })->whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => "Jadwal XI terpilih berhasil dihapus. ({$deletedCount} data dihapus)"]);
    }

    public function deleteAllJadwalXi()
    {
        try {
            $deletedCount = Timetable::whereHas('classSubject.class', function($q) {
                $q->where('grade', '11');
            })->count();
            Timetable::whereHas('classSubject.class', function($q) {
                $q->where('grade', '11');
            })->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Semua data jadwal kelas XI berhasil dihapus! ({$deletedCount} data dihapus)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua data jadwal XI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            Log::info('Export Jadwal XI request received', $request->all());

            // Validate format parameter
            $format = $request->get('format', 'pdf');
            if (!in_array($format, ['pdf'])) {
                $format = 'pdf';
            }

            // Get filter parameters
            $termId = $request->get('term_id');
            $classFilter = $request->get('class');
            $groupType = $request->get('group_type');
            $weekType = $request->get('week_type');
            $locationType = $request->get('location_type');
            $day = $request->get('day');

            $days = [
                1 => 'Senin',
                2 => 'Selasa', 
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            $query = Timetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user', 'term'])
                ->whereHas('classSubject.class', function($q) {
                    $q->where('grade', '11');
                });

            // Apply filters
            if ($termId) {
                $query->where('term_id', $termId);
            }

            if ($groupType && $groupType !== 'all' && $groupType !== '') {
                $query->groupType($groupType);
            }

            if ($weekType && $weekType !== 'all' && $weekType !== '') {
                $query->weekType($weekType);
            }

            if ($locationType && $locationType !== 'all' && $locationType !== '') {
                $query->locationType($locationType);
            }

            if ($classFilter && $classFilter !== 'all' && $classFilter !== '') {
                $query->whereHas('classSubject.class', function($q) use ($classFilter) {
                    $q->where('name', $classFilter);
                });
            }

            if ($day && $day !== 'all' && $day !== '') {
                $dayNumber = array_search($day, $days);
                if ($dayNumber !== false) {
                    $query->where('day_of_week', $dayNumber);
                }
            }

            $timetables = $query->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            // Use the same grouping logic as index method
            $jadwals = $this->groupTimetablesForDisplay($timetables, $days);

            // Sort by day and time
            $sortedJadwals = $jadwals->sortBy(function($item) use ($days) {
                $dayOrder = array_flip($days);
                $dayNum = $dayOrder[$item['hari']] ?? 99;
                $timeParts = explode(' - ', $item['jam']);
                $startTime = isset($timeParts[0]) ? strtotime($timeParts[0]) : 0;
                return $dayNum * 100000 + $startTime;
            })->values();

            // Get term info
            $term = $termId ? Term::find($termId) : Term::where('is_active', true)->first();
            $termName = $term ? $term->name : 'Semester Aktif';

            // Get group info for Kelas XI
            $groupInfo = [];
            if ($groupType && $groupType !== 'all' && $groupType !== '') {
                $groupInfo['type'] = $groupType === 'A' ? 'Kelompok A' : 'Kelompok B';
            } else {
                $groupInfo['type'] = 'Semua Kelompok';
            }
            if ($weekType && $weekType !== 'all' && $weekType !== '') {
                $groupInfo['week'] = $weekType === 'ganjil' ? 'Minggu Ganjil' : 'Minggu Genap';
            } else {
                $groupInfo['week'] = 'Semua Minggu';
            }

            // Generate filename
            $filename = 'jadwal_kelas_xi_' . date('Ymd') . '.pdf';

            // Generate PDF
            $pdf = Pdf::loadView('admin.jadwal-pdf', [
                'jadwals' => $sortedJadwals,
                'termName' => $termName,
                'grade' => 'XI',
                'groupInfo' => $groupInfo,
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
