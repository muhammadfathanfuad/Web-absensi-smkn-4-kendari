<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\XiTimetable;
use App\Models\XiClass;
use App\Models\Term;
use App\Imports\XiTimetableImport;
use Maatwebsite\Excel\Facades\Excel;

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

        $query = XiTimetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user'])
            ->xiClasses();

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

        $timetables = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Check if we have any data
        if ($timetables->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data jadwal kelas XI yang ditemukan. Silakan import data terlebih dahulu.',
                'data' => []
            ]);
        }

        // Group timetables by day and time for display
        $groupedTimetables = $this->groupTimetablesForDisplay($timetables, $days);

        return response()->json($groupedTimetables);
    }

    private function groupTimetablesForDisplay($timetables, $days)
    {
        // Group by day_of_week, class_subject_id, type, group_type, location_type, week_alternation
        // This ensures that different groups, locations, and week alternations are treated as separate entries
        $grouped = $timetables->groupBy(function ($item) {
            return $item->day_of_week . '-' . 
                   $item->class_subject_id . '-' . 
                   ($item->type ?? 'teori') . '-' . 
                   ($item->group_type ?? '') . '-' . 
                   ($item->location_type ?? '') . '-' . 
                   ($item->week_alternation ?? '');
        });

        $jadwals = collect();

        foreach ($grouped as $group) {
            // Sort by start_time
            $sortedGroup = $group->sortBy('start_time');

            // Merge consecutive times within each group
            $mergedTimes = [];
            $currentStart = null;
            $currentEnd = null;

            foreach ($sortedGroup as $jadwal) {
                if ($currentStart === null) {
                    $currentStart = $jadwal->start_time;
                    $currentEnd = $jadwal->end_time;
                } else {
                    // Convert times to comparable format for proper comparison
                    $currentEndTime = is_object($currentEnd) ? $currentEnd->format('H:i:s') : $currentEnd;
                    $jadwalStartTime = is_object($jadwal->start_time) ? $jadwal->start_time->format('H:i:s') : $jadwal->start_time;
                    
                    if ($jadwalStartTime === $currentEndTime) {
                        $currentEnd = $jadwal->end_time;
                    } else {
                        $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
                        $currentStart = $jadwal->start_time;
                        $currentEnd = $jadwal->end_time;
                    }
                }
            }
            if ($currentStart !== null) {
                $mergedTimes[] = ['start' => $currentStart, 'end' => $currentEnd];
            }

            // Create entries for each merged time
            foreach ($mergedTimes as $time) {
                $firstJadwal = $sortedGroup->first();
                
                // Format time using the same method as class 10
                $formattedStart = $this->formatTimeForDisplay($time['start']);
                $formattedEnd = $this->formatTimeForDisplay($time['end']);
                
                $jadwals->push([
                    'id' => $firstJadwal->id, // Use first id for actions
                    'hari' => $days[$firstJadwal->day_of_week] ?? '-',
                    'jam' => $formattedStart . ' - ' . $formattedEnd,
                    'kelas' => $this->formatClassName($firstJadwal->classSubject?->class?->name ?? '-', $firstJadwal->classSubject?->class?->grade ?? ''),
                    'mapel' => $firstJadwal->classSubject?->subject?->name ?? '-',
                    'guru' => $firstJadwal->classSubject?->teacher?->user?->full_name ?? '-',
                    'jenis' => ucfirst($firstJadwal->type ?? 'teori'),
                    'kelompok' => $firstJadwal->group_type_display,
                    'lokasi' => $firstJadwal->location_type_display,
                    'minggu' => $firstJadwal->week_alternation_display,
                ]);
            }
        }

        // Additional step: Merge identical entries (same subject, teacher, etc.)
        // This handles cases where identical entries are separated by small time gaps
        $finalJadwals = collect();
        $processedEntries = [];

        foreach ($jadwals as $currentEntry) {
            $key = $currentEntry['mapel'] . '|' . 
                   $currentEntry['guru'] . '|' . 
                   $currentEntry['kelas'] . '|' . 
                   $currentEntry['jenis'] . '|' . 
                   $currentEntry['kelompok'] . '|' . 
                   $currentEntry['lokasi'] . '|' . 
                   $currentEntry['minggu'];

            if (isset($processedEntries[$key])) {
                // Merge time ranges - take the earliest start and latest end
                $existingTime = $processedEntries[$key]['jam'];
                $currentTime = $currentEntry['jam'];
                
                // Extract start and end times
                $existingTimes = explode(' - ', $existingTime);
                $currentTimes = explode(' - ', $currentTime);
                
                if (count($existingTimes) === 2 && count($currentTimes) === 2) {
                    // Compare times to get the earliest start and latest end
                    $start1 = $this->parseTime($existingTimes[0]);
                    $end1 = $this->parseTime($existingTimes[1]);
                    $start2 = $this->parseTime($currentTimes[0]);
                    $end2 = $this->parseTime($currentTimes[1]);
                    
                    $earliestStart = $start1 <= $start2 ? $existingTimes[0] : $currentTimes[0];
                    $latestEnd = $end1 >= $end2 ? $existingTimes[1] : $currentTimes[1];
                    
                    $newTime = $earliestStart . ' - ' . $latestEnd;
                    $processedEntries[$key]['jam'] = $newTime;
                }
            } else {
                $processedEntries[$key] = $currentEntry;
            }
        }

        // Convert processed entries back to collection
        foreach ($processedEntries as $entry) {
            $finalJadwals->push($entry);
        }

        return $finalJadwals;
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
        ]);

        try {
            // Check if there's an active term
            $term = Term::where('is_active', true)->latest()->first();
            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada term aktif. Silakan buat term aktif terlebih dahulu.'
                ], 400);
            }

            $import = new XiTimetableImport($request->group_type, $request->grade);
            Excel::import($import, $request->file('file'));

            // Get the processed count
            $processedCount = $import->getProcessedCount();
            $errors = $import->getErrors();

            if ($processedCount > 0) {
                $groupText = "Kelompok {$request->group_type}";
                $gradeText = " untuk Kelas {$request->grade}";
                
                $message = "Jadwal berhasil diimport! {$processedCount} entri diproses untuk {$groupText}{$gradeText}.";
                
                if (!empty($errors)) {
                    $message .= " Terdapat " . count($errors) . " error yang perlu diperhatikan.";
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'processedCount' => $processedCount,
                    'errors' => $errors
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang diproses. Periksa format file Excel dan pastikan kode guru serta kode pelajaran sudah ada di database.'
                ], 400);
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
        $totalTimetables = XiTimetable::xiClasses()->count();
        $groupA = XiTimetable::xiClasses()->groupType('A')->count();
        $groupB = XiTimetable::xiClasses()->groupType('B')->count();
        $labSessions = XiTimetable::xiClasses()->locationType('lab')->count();
        $theorySessions = XiTimetable::xiClasses()->locationType('theory')->count();

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
        $timetable = XiTimetable::find($id);
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
        $existingCount = XiTimetable::xiClasses()->whereIn('id', $ids)->count();
        
        if ($existingCount === 0) {
            return response()->json(['success' => false, 'message' => 'Jadwal XI tidak ditemukan.'], 404);
        }

        $deletedCount = XiTimetable::xiClasses()->whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => "Jadwal XI terpilih berhasil dihapus. ({$deletedCount} data dihapus)"]);
    }

    public function deleteAllJadwalXi()
    {
        try {
            $deletedCount = XiTimetable::xiClasses()->count();
            XiTimetable::xiClasses()->delete();
            
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
}
