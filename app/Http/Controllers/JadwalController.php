<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Timetable;
use App\Models\Term;
use App\Imports\TimetableImport;
use App\Imports\SubjectsImport;
use App\Imports\EnhancedTimetableImport;
use App\Imports\ClassesImport;
use Maatwebsite\Excel\Facades\Excel;
class JadwalController extends Controller
{
    public function index()
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        $timetables = Timetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user'])
            ->whereHas('classSubject.class', function($query) {
                $query->where('grade', '10');
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Group by day_of_week, class_subject_id, type
        $grouped = $timetables->groupBy(function ($item) {
            return $item->day_of_week . '-' . $item->class_subject_id . '-' . ($item->type ?? 'teori');
        });

        $jadwals = collect();

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
                $jadwals->push([
                    'id' => $firstJadwal->id, // Use first id for actions
                    'hari' => $days[$firstJadwal->day_of_week] ?? '-',
                    'jam' => $time['start'] . ' - ' . $time['end'],
                    'kelas' => $this->formatClassName($firstJadwal->classSubject?->class?->name ?? '-', $firstJadwal->classSubject?->class?->grade ?? ''),
                    'mapel' => $firstJadwal->classSubject?->subject?->name ?? '-',
                    'guru' => $firstJadwal->classSubject?->teacher?->user?->full_name ?? '-',
                    'jenis' => ucfirst($firstJadwal->type ?? 'teori'),
                ]);
            }
        }

        return response()->json($jadwals);
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
            'grade' => 'required|in:X,XI,XII',
            'week_type' => 'nullable|in:ganjil,genap',
        ]);

        // Validate week_type for XI and XII
        if (in_array($request->grade, ['XI', 'XII']) && empty($request->week_type)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe minggu harus dipilih untuk kelas XI dan XII.'
            ], 400);
        }

        try {
            // Check if there's an active term
            $term = Term::where('is_active', true)->latest()->first();
            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada term aktif. Silakan buat term aktif terlebih dahulu.'
                ], 400);
            }

            $import = new EnhancedTimetableImport($request->grade, $request->week_type);
            Excel::import($import, $request->file('file'));

            // Get the processed count
            $processedCount = $import->getProcessedCount();

            if ($processedCount > 0) {
                $weekTypeText = $request->week_type ? " (Minggu: " . ucfirst($request->week_type) . ")" : "";
                return response()->json([
                    'success' => true,
                    'message' => "Jadwal berhasil diimport! {$processedCount} entri diproses untuk kelas {$request->grade}{$weekTypeText}."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang diproses. Periksa format file Excel dan pastikan kode guru serta kode pelajaran sudah ada di database.'
                ], 400);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            Log::error('Import jadwal validation error: ' . $e->getMessage());
            
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi file gagal: ' . implode('; ', $errorMessages)
            ], 400);
        } catch (\Exception $e) {
            Log::error('Import jadwal error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $jadwal = Timetable::whereHas('classSubject.class', function($query) {
            $query->where('grade', '10');
        })->findOrFail($id);
        $jadwal->update($request->only(['type']));
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        try {
            $jadwal = Timetable::whereHas('classSubject.class', function($query) {
                $query->where('grade', '10');
            })->findOrFail($id);
            $jadwal->delete();
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = explode(',', $request->ids);
        $ids = array_filter($ids, function ($id) {
            return !empty($id) && is_numeric($id);
        });
        $ids = array_map('intval', $ids);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada ID yang valid dipilih']);
        }

        try {
            Timetable::whereHas('classSubject.class', function($query) {
                $query->where('grade', '10');
            })->whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Jadwal yang dipilih berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()], 500);
        }
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:subjects,code',
            'name' => 'required|string|max:255',
        ]);

        try {
            \App\Models\Subject::create([
                'code' => $request->code,
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadSubjects(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:10240',
        ]);

        try {
            $import = new SubjectsImport();
            Excel::import($import, $request->file('file'));

            $processedCount = $import->getProcessedCount();
            $errors = $import->getErrors();

            if ($processedCount > 0) {
                $message = "File mata pelajaran berhasil diproses! {$processedCount} mata pelajaran diimport.";
                
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
                    'message' => 'Tidak ada data yang diproses. Periksa format file Excel dan pastikan data sudah sesuai.',
                    'errors' => $errors
                ], 400);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi file gagal: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubjectsByClass()
    {
        // Get all subjects with their class assignments
        $subjects = \App\Models\Subject::with(['classSubjects.class', 'classSubjects.teacher.user'])
            ->get();

        $subjectsData = [];

        foreach ($subjects as $subject) {
            $classes = [];
            $teachers = [];

            foreach ($subject->classSubjects as $classSubject) {
                if ($classSubject->class) {
                    $className = $classSubject->class->name;
                    $grade = $classSubject->class->grade;
                    $teacherName = $classSubject->teacher ? $classSubject->teacher->user->full_name : '-';
                    
                    $classes[] = [
                        'name' => $className,
                        'grade' => $grade
                    ];
                    
                    if ($teacherName !== '-' && !in_array($teacherName, $teachers)) {
                        $teachers[] = $teacherName;
                    }
                }
            }

            $subjectsData[] = [
                'id' => $subject->id,
                'code' => $subject->code,
                'name' => $subject->name,
                'classes' => $classes,
                'teachers' => $teachers,
                'class_count' => count($classes),
                'teacher_count' => count($teachers)
            ];
        }

        return response()->json($subjectsData);
    }

    public function showSubject($id)
    {
        try {
            $subject = \App\Models\Subject::with(['classSubjects.class', 'classSubjects.teacher.user'])->findOrFail($id);
            
            $classes = [];
            $teachers = [];

            foreach ($subject->classSubjects as $classSubject) {
                if ($classSubject->class) {
                    $classes[] = [
                        'name' => $classSubject->class->name,
                        'grade' => $classSubject->class->grade
                    ];
                    
                    if ($classSubject->teacher && $classSubject->teacher->user) {
                        $teachers[] = [
                            'user' => [
                                'full_name' => $classSubject->teacher->user->full_name,
                                'email' => $classSubject->teacher->user->email
                            ]
                        ];
                    }
                }
            }

            $subjectData = [
                'id' => $subject->id,
                'code' => $subject->code,
                'name' => $subject->name,
                'classes' => $classes,
                'teachers' => $teachers,
                'class_count' => count($classes),
                'teacher_count' => count($teachers)
            ];
            
            return response()->json($subjectData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSubject(Request $request, $id)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:10',
                'name' => 'required|string|max:255',
            ]);

            $subject = \App\Models\Subject::findOrFail($id);
            $subject->update([
                'code' => $request->code,
                'name' => $request->name,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil diperbarui.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroySubject($id)
    {
        try {
            $subject = \App\Models\Subject::findOrFail($id);
            $subject->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClasses()
    {
        try {
            $classes = \App\Models\Classroom::orderBy('grade')->orderBy('name')->get();
            
            $classesData = $classes->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'grade' => $class->grade,
                    'display_grade' => $this->formatClassName($class->name, $class->grade),
                    'created_at' => $class->created_at->format('Y-m-d H:i:s'),
                ];
            });
            
            return response()->json($classesData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showClass($id)
    {
        try {
            $class = \App\Models\Classroom::findOrFail($id);
            
            $classData = [
                'id' => $class->id,
                'name' => $class->name,
                'grade' => $class->grade,
                'display_grade' => $this->formatClassName($class->name, $class->grade),
                'created_at' => $class->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $class->updated_at->format('Y-m-d H:i:s'),
            ];
            
            return response()->json($classData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateClass(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'grade' => 'required|string|in:10,11,12',
            ]);

            $class = \App\Models\Classroom::findOrFail($id);
            
            $class->update([
                'name' => $request->name,
                'grade' => $request->grade,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyClass($id)
    {
        try {
            $class = \App\Models\Classroom::findOrFail($id);
            $class->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importClasses(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:10240', // 10MB max
            ]);

            $import = new ClassesImport();
            Excel::import($import, $request->file('file'));

            $processedCount = $import->getProcessedCount();
            $errors = $import->getErrors();

            if ($processedCount > 0) {
                $message = "Berhasil mengimpor {$processedCount} kelas.";
                if (count($errors) > 0) {
                    $message .= " Terdapat " . count($errors) . " error yang diabaikan.";
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'processed_count' => $processedCount,
                    'errors' => $errors
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil diimpor. Periksa format file Excel.',
                    'errors' => $errors
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error importing classes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAllSubjects()
    {
        try {
            $deletedCount = \App\Models\Subject::count();
            \App\Models\Subject::query()->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Semua data mata pelajaran berhasil dihapus! ({$deletedCount} data dihapus)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua data mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAllClasses()
    {
        try {
            $deletedCount = \App\Models\Classroom::count();
            \App\Models\Classroom::query()->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Semua data kelas berhasil dihapus! ({$deletedCount} data dihapus)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua data kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAllJadwal()
    {
        try {
            $deletedCount = \App\Models\Timetable::count();
            \App\Models\Timetable::query()->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Semua data jadwal kelas X berhasil dihapus! ({$deletedCount} data dihapus)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua data jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
