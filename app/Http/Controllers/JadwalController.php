<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Timetable;
use App\Models\Term;
use App\Models\Subject;
use App\Imports\TimetableImport;
use App\Imports\SubjectsImport;
use App\Imports\EnhancedTimetableImport;
use App\Imports\ClassesImport;
use Maatwebsite\Excel\Facades\Excel;
class JadwalController extends Controller
{
    public function jadwalPelajaran()
    {
        return view('admin.jadwal-pelajaran');
    }

    public function jadwalPelajaranXi()
    {
        return view('admin.jadwal-pelajaran-xi');
    }

    public function index(Request $request)
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

        $query = Timetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user', 'term'])
            ->whereHas('classSubject.class', function($query) {
                $query->where('grade', '10');
            });

        // Filter by term_id if provided
        if ($request->has('term_id') && $request->term_id) {
            \Log::info('Filtering by term_id: ' . $request->term_id);
            $query->where('term_id', $request->term_id);
        } else {
            \Log::info('No term_id filter applied');
        }

        $timetables = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        \Log::info('Found ' . $timetables->count() . ' timetables');

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
            'term_id' => 'required|exists:terms,id',
        ]);

        // Validate week_type for XI and XII
        if (in_array($request->grade, ['XI', 'XII']) && empty($request->week_type)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe minggu harus dipilih untuk kelas XI dan XII.'
            ], 400);
        }

        try {
            // Get the selected term
            $term = Term::find($request->term_id);
            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semester yang dipilih tidak ditemukan.'
                ], 400);
            }

            $import = new EnhancedTimetableImport($request->grade, $request->week_type, $term->id);
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

    public function editJadwal($id)
    {
        try {
            $jadwal = Timetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user'])
                ->findOrFail($id);
            
            // Check if classSubject exists
            if (!$jadwal->classSubject) {
                return response()->json(['success' => false, 'message' => 'Data jadwal tidak lengkap - tidak ada relasi dengan mata pelajaran']);
            }
            
            return response()->json([
                'success' => true,
                'jadwal' => [
                    'id' => $jadwal->id,
                    'day_of_week' => $jadwal->day_of_week,
                    'start_time' => $jadwal->start_time,
                    'end_time' => $jadwal->end_time,
                    'class_id' => $jadwal->classSubject->class_id,
                    'subject_id' => $jadwal->classSubject->subject_id,
                    'teacher_id' => $jadwal->classSubject->teacher_id,
                    'week_type' => $jadwal->week_type,
                    'type' => $jadwal->type
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat data jadwal: ' . $e->getMessage()]);
        }
    }

    public function updateJadwal(Request $request, $id)
    {
        try {
            $request->validate([
                'day_of_week' => 'required|integer|between:1,6',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'class_id' => 'required|exists:classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,user_id',
                'week_type' => 'nullable|in:ganjil,genap'
            ]);

            $jadwal = Timetable::findOrFail($id);
            
            // Check for conflicts (excluding current jadwal)
            $conflict = Timetable::where('id', '!=', $id)
                ->where('day_of_week', $request->day_of_week)
                ->where('start_time', $request->start_time)
                ->where('end_time', $request->end_time)
                ->whereHas('classSubject', function($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                })
                ->exists();

            if ($conflict) {
                return response()->json(['success' => false, 'message' => 'Konflik jadwal terdeteksi!']);
            }

            // Update timetable
            $jadwal->update([
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'week_type' => $request->week_type
            ]);

            // Update class subject
            $jadwal->classSubject->update([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id
            ]);

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage()]);
        }
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

    public function getSubjects()
    {
        try {
            $subjects = Subject::orderBy('name')->get();
            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat mata pelajaran']);
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

    public function storeClass(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'grade' => 'required|string|in:10,11,12',
            ]);

            $class = \App\Models\Classroom::create([
                'name' => $request->name,
                'grade' => $request->grade,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan.',
                'data' => [
                    'id' => $class->id,
                    'name' => $class->name,
                    'grade' => $class->grade,
                    'display_grade' => $this->formatClassName($class->name, $class->grade),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kelas: ' . $e->getMessage()
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
            // Count timetables for grade 10 classes only
            $deletedCount = \App\Models\Timetable::whereHas('classSubject.class', function($query) {
                $query->where('grade', '10');
            })->count();
            
            // Delete timetables for grade 10 classes only
            \App\Models\Timetable::whereHas('classSubject.class', function($query) {
                $query->where('grade', '10');
            })->delete();
            
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

    // Method untuk mendapatkan data guru, mata pelajaran, dan kelas untuk form manual
    public function getManualFormData(Request $request)
    {
        try {
            $day = $request->get('day');
            $startTime = $request->get('start_time');
            $endTime = $request->get('end_time');
            $classId = $request->get('class_id');
            $subjectId = $request->get('subject_id');

            // Get teachers with their user data
            $teachersQuery = \App\Models\Teacher::with('user')
                ->whereHas('user', function($query) {
                    $query->where('status', 'active');
                });

            // Get subjects
            $subjectsQuery = \App\Models\Subject::query();

            // Get classes for grade 10 and 11
            $classesQuery = \App\Models\Classroom::whereIn('grade', ['10', '11']);

            // Apply filters based on what's already selected
            if ($day && $startTime && $endTime) {
                // Filter classes that don't have conflicts at this time
                $conflictingClassIds = \App\Models\Timetable::where('day_of_week', $day)
                    ->where(function($query) use ($startTime, $endTime) {
                        $query->where(function($q) use ($startTime, $endTime) {
                            // Check for time overlap - more precise overlap detection
                            $q->where('start_time', '<', $endTime)
                              ->where('end_time', '>', $startTime);
                        });
                    })
                    ->whereHas('classSubject.class', function($query) {
                        $query->whereIn('grade', ['10', '11']);
                    })
                    ->pluck('class_subject_id')
                    ->toArray();

                $conflictingClassroomIds = \App\Models\ClassSubject::whereIn('id', $conflictingClassIds)
                    ->pluck('class_id')
                    ->toArray();

                $classesQuery->whereNotIn('id', $conflictingClassroomIds);
                
                // Add debug info for development
                if (config('app.debug')) {
                    Log::info('Filtering classes for day: ' . $day . ', time: ' . $startTime . '-' . $endTime);
                    Log::info('Conflicting class IDs: ' . json_encode($conflictingClassroomIds));
                }
            }

            if ($day && $startTime && $endTime && $classId) {
                // Filter subjects that are not already scheduled for this class at this time
                $scheduledSubjectIds = \App\Models\Timetable::where('day_of_week', $day)
                    ->where(function($query) use ($startTime, $endTime) {
                        $query->where(function($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<', $endTime)
                              ->where('end_time', '>', $startTime);
                        });
                    })
                    ->whereHas('classSubject', function($query) use ($classId) {
                        $query->where('class_id', $classId);
                    })
                    ->pluck('class_subject_id')
                    ->toArray();

                $scheduledSubjects = \App\Models\ClassSubject::whereIn('id', $scheduledSubjectIds)
                    ->pluck('subject_id')
                    ->toArray();

                $subjectsQuery->whereNotIn('id', $scheduledSubjects);
            }

            if ($day && $startTime && $endTime && $classId && $subjectId) {
                // First, get teachers who teach this subject (including those with null class_id)
                $subjectTeachers = \App\Models\ClassSubject::where('subject_id', $subjectId)
                    ->where(function($query) use ($classId) {
                        $query->where('class_id', $classId)
                              ->orWhereNull('class_id');
                    })
                    ->pluck('teacher_id')
                    ->unique()
                    ->values()
                    ->toArray();

                // Filter teachers who are not already scheduled at this time
                $busyTeacherIds = \App\Models\Timetable::where('day_of_week', $day)
                    ->where(function($query) use ($startTime, $endTime) {
                        $query->where(function($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<', $endTime)
                              ->where('end_time', '>', $startTime);
                        });
                    })
                    ->whereHas('classSubject', function($query) {
                        $query->whereHas('class', function($q) {
                            $q->whereIn('grade', ['10', '11']);
                        });
                    })
                    ->pluck('class_subject_id')
                    ->unique()
                    ->values()
                    ->toArray();

                $busyTeachers = \App\Models\ClassSubject::whereIn('id', $busyTeacherIds)
                    ->pluck('teacher_id')
                    ->unique()
                    ->values()
                    ->toArray();

                // Apply both filters: teachers who teach this subject AND are not busy
                $teachersQuery->whereIn('user_id', $subjectTeachers)
                             ->whereNotIn('user_id', $busyTeachers);
            }

            $teachers = $teachersQuery->get()->map(function($teacher) {
                return [
                    'id' => $teacher->user_id,
                    'name' => $teacher->user->full_name,
                    'nip' => $teacher->nip,
                    'kode_guru' => $teacher->kode_guru
                ];
            });

            $subjects = $subjectsQuery->orderBy('name')->get();

            $classes = $classesQuery->orderBy('grade')->orderBy('name')->get()->map(function($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'grade' => $class->grade,
                    'display_name' => $this->formatClassName($class->name, $class->grade)
                ];
            });

            $responseData = [
                'teachers' => $teachers,
                'subjects' => $subjects,
                'classes' => $classes
            ];

            // Add conflict information if filtering is applied
            if ($day && $startTime && $endTime) {
                $conflictingClasses = \App\Models\Classroom::whereIn('grade', ['10', '11'])
                    ->whereIn('id', $conflictingClassroomIds ?? [])
                    ->get()
                    ->map(function($class) {
                        return [
                            'id' => $class->id,
                            'name' => $class->name,
                            'grade' => $class->grade,
                            'display_name' => $this->formatClassName($class->name, $class->grade)
                        ];
                    });

                $responseData['conflicting_classes'] = $conflictingClasses;
                $responseData['filter_info'] = [
                    'day' => $day,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'total_classes' => \App\Models\Classroom::whereIn('grade', ['10', '11'])->count(),
                    'available_classes' => count($classes),
                    'conflicting_classes_count' => count($conflictingClasses)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data form: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk menyimpan mata pelajaran manual
    public function storeManualClassSubject(Request $request)
    {
        try {
            $request->validate([
                'class_id' => 'required|exists:classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,user_id',
                'day_of_week' => 'required|integer|between:1,6',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'class_type' => 'nullable|in:teori,praktik',
                'week_type' => 'nullable|in:ganjil,genap',
                'term_id' => 'required|exists:terms,id',
            ]);

            // Get class grade to determine if additional fields are required
            $class = \App\Models\Classroom::find($request->class_id);
            
            // Validate additional fields for grade 11
            if ($class && $class->grade === '11') {
                if (empty($request->class_type)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jenis kelas harus dipilih untuk kelas XI!'
                    ], 422);
                }
                if (empty($request->week_type)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipe minggu harus dipilih untuk kelas XI!'
                    ], 422);
                }
            }

            // Check if combination already exists
            $existingTimetable = \App\Models\Timetable::whereHas('classSubject', function($query) use ($request) {
                $query->where('class_id', $request->class_id)
                      ->where('subject_id', $request->subject_id)
                      ->where('teacher_id', $request->teacher_id);
            })->where([
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ])->first();

            if ($existingTimetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal dengan kombinasi yang sama sudah ada!'
                ], 422);
            }

                // Check if ClassSubject already exists with this combination
                $classSubject = \App\Models\ClassSubject::where([
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'teacher_id' => $request->teacher_id
                ])->first();

                if (!$classSubject) {
                    // Check if there's an existing record with null class_id for this teacher and subject
                    $existingClassSubject = \App\Models\ClassSubject::where('teacher_id', $request->teacher_id)
                        ->where('subject_id', $request->subject_id)
                        ->whereNull('class_id')
                        ->first();

                    if ($existingClassSubject) {
                        // Update the existing record
                        $existingClassSubject->update(['class_id' => $request->class_id]);
                        $classSubject = $existingClassSubject;
                    } else {
                        // Create new record
                        $classSubject = \App\Models\ClassSubject::create([
                            'class_id' => $request->class_id,
                            'subject_id' => $request->subject_id,
                            'teacher_id' => $request->teacher_id
                        ]);
                    }
                }

            // Get the selected term
            $activeTerm = Term::find($request->term_id);
            if (!$activeTerm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semester yang dipilih tidak ditemukan.'
                ], 400);
            }

            // Create timetable entry
            $timetableData = [
                'class_subject_id' => $classSubject->id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'type' => $request->class_type ?? 'teori',
                'term_id' => $activeTerm->id,
            ];

            // Add additional fields for grade 11
            if ($class && $class->grade === '11') {
                if ($request->week_type) {
                    $timetableData['week_type'] = $request->week_type;
                    $timetableData['week_alternation'] = $request->week_type; // Map week_type to week_alternation
                }
                
                // Set values for XI-specific fields
                $timetableData['group_type'] = $class->group_type;
                
                // If group_type is not set in class, determine it from class name pattern
                if (empty($timetableData['group_type'])) {
                    $className = $class->name;
                    if (str_ends_with($className, 'A') || str_ends_with($className, 'C')) {
                        $timetableData['group_type'] = 'A';
                    } elseif (str_ends_with($className, 'B')) {
                        $timetableData['group_type'] = 'B';
                    }
                }
                
                $timetableData['location_type'] = $class->location_preference ?? ($request->class_type === 'praktik' ? 'lab' : 'theory');
            }

            $timetable = \App\Models\Timetable::create($timetableData);

            // Get the created data with relationships
            $timetable = \App\Models\Timetable::with(['classSubject.class', 'classSubject.subject', 'classSubject.teacher.user'])
                ->find($timetable->id);

            $days = [
                1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
            ];

            return response()->json([
                'success' => true,
                'message' => 'Jadwal mata pelajaran berhasil ditambahkan!',
                'data' => [
                    'id' => $timetable->id,
                    'class' => $this->formatClassName($timetable->classSubject->class->name, $timetable->classSubject->class->grade),
                    'subject' => $timetable->classSubject->subject->name,
                    'teacher' => $timetable->classSubject->teacher->user->full_name,
                    'day' => $days[$timetable->day_of_week],
                    'time' => $timetable->start_time . ' - ' . $timetable->end_time,
                    'type' => ucfirst($timetable->type ?? 'teori')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if ($e->getCode() == 23000) { // Integrity constraint violation
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kombinasi kelas, mata pelajaran, dan guru sudah ada dalam sistem. Silakan pilih kombinasi yang berbeda.'
                    ], 422);
                }
            }
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mendapatkan data class subjects yang sudah ada
    public function getClassSubjects()
    {
        try {
            $classSubjects = \App\Models\ClassSubject::with(['class', 'subject', 'teacher.user'])
                ->whereHas('class', function($query) {
                    $query->whereIn('grade', ['10', '11']);
                })
                ->orderBy('class_id')
                ->orderBy('subject_id')
                ->get()
                ->map(function($classSubject) {
                    return [
                        'id' => $classSubject->id,
                        'class' => $this->formatClassName($classSubject->class->name, $classSubject->class->grade),
                        'subject' => $classSubject->subject->name,
                        'teacher' => $classSubject->teacher->user->full_name,
                        'grade' => $classSubject->class->grade
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $classSubjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk menghapus class subject
    public function destroyClassSubject($id)
    {
        try {
            $classSubject = \App\Models\ClassSubject::findOrFail($id);
            $classSubject->delete();
            
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
}
