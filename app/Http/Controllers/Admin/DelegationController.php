<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionDelegation;
use App\Models\Timetable;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Notification;
use App\Models\TeacherLeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DelegationController extends Controller
{
    // Menampilkan halaman manajemen delegasi
    public function index()
    {
        try {
        $delegations = SessionDelegation::with([
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetable.classSubject.teacher.user',
            'originalTeacher.user',
            'delegatedTo',
            'createdBy'
        ])
            ->whereHas('timetable')
        ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($delegation) {
                return is_object($delegation) && isset($delegation->id) && $delegation->timetable;
            });
        
        // Get teacher leave requests
        $teacherLeaveRequests = TeacherLeaveRequest::with([
            'teacher',
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetable.classSubject.teacher.user',
                'timetables.timetable.classSubject.subject',
                'timetables.timetable.classSubject.class',
            'substitute',
            'processedBy'
        ])
            ->whereNotNull('teacher_id')
            ->whereHas('teacher')
        ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($request) {
                return is_object($request) && isset($request->id);
            });
        
        $timetables = Timetable::with([
            'classSubject.subject',
            'classSubject.class',
            'classSubject.teacher.user'
            ])
            ->whereHas('classSubject')
            ->get()
            ->map(function($timetable) {
                // Ensure all relationships are properly loaded
                if (!$timetable || !is_object($timetable) || !isset($timetable->id) || !$timetable->classSubject) {
                    return null;
                }
                try {
                    return [
                        'id' => $timetable->id,
                        'day_of_week' => $timetable->day_of_week,
                        'start_time' => $timetable->start_time,
                        'end_time' => $timetable->end_time,
                        'class_subject' => [
                            'id' => $timetable->classSubject->id,
                            'subject_id' => $timetable->classSubject->subject_id ?? null,
                            'class_id' => $timetable->classSubject->class_id ?? null,
                            'subject' => ($timetable->classSubject->subject && is_object($timetable->classSubject->subject)) ? [
                                'id' => $timetable->classSubject->subject->id,
                                'name' => $timetable->classSubject->subject->name
                            ] : null,
                            'class' => ($timetable->classSubject->class && is_object($timetable->classSubject->class)) ? [
                                'id' => $timetable->classSubject->class->id,
                                'name' => $timetable->classSubject->class->name
                            ] : null,
                            'teacher' => ($timetable->classSubject->teacher && is_object($timetable->classSubject->teacher) && $timetable->classSubject->teacher->user && is_object($timetable->classSubject->teacher->user)) ? [
                                'user_id' => $timetable->classSubject->teacher->user_id,
                                'full_name' => $timetable->classSubject->teacher->user->full_name ?? null
                            ] : null
                        ]
                    ];
                } catch (\Exception $e) {
                    \Log::warning('Error processing timetable: ' . $e->getMessage(), ['timetable_id' => $timetable->id ?? null]);
                    return null;
                }
            })
            ->filter(); // Remove null values
        
            // Get unique subjects - ensure all are valid objects
            $subjects = \App\Models\Subject::orderBy('name')->get()->filter(function($subject) {
                return is_object($subject) && isset($subject->id);
            });
        
            // Get unique classes - ensure all are valid objects
            $classes = \App\Models\Classroom::orderBy('name')->get()->filter(function($class) {
                return is_object($class) && isset($class->id);
            });
        
            // Get teachers - ensure all are valid objects
            $teachers = Teacher::with('user')->get()->filter(function($teacher) {
                return is_object($teacher) && isset($teacher->id);
            });
        
        $users = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['guru', 'murid']);
            })->with(['roles', 'teacher', 'student'])->get()->filter(function($user) {
                return is_object($user) && isset($user->id);
            });
        
        return view('admin.delegasi', compact('delegations', 'teacherLeaveRequests', 'timetables', 'users', 'subjects', 'classes', 'teachers'));
        } catch (\Exception $e) {
            \Log::error('Error in DelegationController@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return view with empty collections to prevent further errors
            return view('admin.delegasi', [
                'delegations' => collect(),
                'teacherLeaveRequests' => collect(),
                'timetables' => collect(),
                'users' => collect(),
                'subjects' => collect(),
                'classes' => collect(),
                'teachers' => collect()
            ]);
        }
    }

    // Simpan delegasi baru
    public function store(Request $request)
    {
        try {
            // Check if schedule_delegations (with different delegates per schedule) are provided
            $scheduleDelegationsJson = $request->input('schedule_delegations');
            if ($scheduleDelegationsJson) {
                $scheduleDelegations = json_decode($scheduleDelegationsJson, true);
                if (is_array($scheduleDelegations) && !empty($scheduleDelegations)) {
                    // Multiple timetables with different delegates mode
                    return $this->storeMultipleDelegationsWithDifferentDelegates($request, $scheduleDelegations);
                }
            }
            
            // Check if multiple timetable_ids are provided (same delegate for all)
            $timetableIds = $request->input('timetable_ids', []);
            
            if (!empty($timetableIds) && is_array($timetableIds)) {
                // Multiple timetables mode (same delegate)
                return $this->storeMultipleDelegations($request, $timetableIds);
            }
            
            // Check if class_subject_id and day_of_week are provided (new approach - one delegation per subject)
            if ($request->has('class_subject_id') && $request->has('day_of_week')) {
                return $this->storeDelegationForClassSubject($request);
            }
            
            // Single timetable mode (original logic - for backward compatibility)
            $validated = $request->validate([
                'timetable_id' => 'required|exists:timetables,id',
                'delegated_to_user_id' => 'required|exists:users,id',
                'type' => 'required|in:permanent,temporary',
                'valid_from' => 'nullable|date',
                'valid_until' => 'nullable|date|after:valid_from',
                'admin_notes' => 'nullable|string|max:500',
            ]);

            // Get timetable with relationships
            $timetable = Timetable::with([
                'classSubject.subject',
                'classSubject.class',
                'classSubject.teacher.user'
            ])->findOrFail($validated['timetable_id']);

            // Validasi 1: Pastikan jadwal ada
            if (!$timetable || !$timetable->classSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan atau tidak valid!'
                ], 400);
            }

            // Validasi 2: Cek guru yang digantikan (original teacher)
            $originalTeacherId = $timetable->classSubject->teacher->user_id;
            if (!$originalTeacherId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru asli untuk jadwal ini tidak ditemukan!'
                ], 400);
            }

            // Validasi 3: Cek email delegasi
            $delegatedUser = User::with('roles', 'teacher', 'student')->find($validated['delegated_to_user_id']);
            if (!$delegatedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email delegasi tidak ditemukan!'
                ], 400);
            }

            $delegatedRole = $delegatedUser->roles->first()->name ?? null;
            
            // Validasi 4: Jika delegasi adalah guru, pastikan guru tidak sedang mengajar di jam yang sama
            if ($delegatedRole === 'teacher') {
                $teacher = $delegatedUser->teacher;
                if (!$teacher) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User yang dipilih bukan guru!'
                    ], 400);
                }

                // Cek apakah guru ini sedang mengajar di jam yang sama
                $conflictingSchedule = Timetable::whereHas('classSubject', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->where('day_of_week', $timetable->day_of_week)
                ->where(function($query) use ($timetable) {
                    $query->whereBetween('start_time', [$timetable->start_time, $timetable->end_time])
                          ->orWhereBetween('end_time', [$timetable->start_time, $timetable->end_time])
                          ->orWhere(function($q) use ($timetable) {
                              $q->where('start_time', '<=', $timetable->start_time)
                                ->where('end_time', '>=', $timetable->end_time);
                          });
                })
                ->where('id', '!=', $validated['timetable_id'])
                ->first();

                if ($conflictingSchedule) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Guru yang dipilih sedang mengajar di jam yang sama!'
                    ], 400);
                }
            }

            // Validasi 5: Jika delegasi adalah murid, pastikan murid dari kelas yang sama
            if ($delegatedRole === 'student') {
                $student = $delegatedUser->student;
                if (!$student) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User yang dipilih bukan murid!'
                    ], 400);
                }

                // Cek apakah murid dari kelas yang sama dengan jadwal
                if ($student->class_id != $timetable->classSubject->class_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Murid yang dipilih bukan dari kelas yang sama dengan jadwal!'
                    ], 400);
                }
            }

            // Validasi 6: Jika role bukan teacher atau student
            if (!in_array($delegatedRole, ['teacher', 'student'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delegasi hanya boleh kepada guru atau murid!'
                ], 400);
            }

            // Jika semua validasi berhasil, simpan delegasi
            $delegation = SessionDelegation::create([
                'timetable_id' => $validated['timetable_id'],
                'original_teacher_id' => $originalTeacherId,
                'delegated_to_user_id' => $validated['delegated_to_user_id'],
                'type' => $validated['type'],
                'valid_from' => $validated['valid_from'] ?? now()->toDateString(),
                'valid_until' => $validated['valid_until'] ?? null,
                'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
                'created_by' => Auth::id(),
                'status' => 'active',
            ]);

            // Buat notifikasi untuk user yang diberikan delegasi
            $subjectName = $timetable->classSubject->subject->name ?? 'Mata Pelajaran';
            $className = $timetable->classSubject->class->name ?? 'Kelas';
            $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
            $dayName = $dayNames[$timetable->day_of_week] ?? 'Hari';
            $timeRange = Carbon::parse($timetable->start_time)->format('H:i') . ' - ' . Carbon::parse($timetable->end_time)->format('H:i');
            
            $notificationTitle = 'Tugas Delegasi Baru';
            $notificationMessage = "Anda mendapat tugas delegasi untuk mengajar {$subjectName} - {$className} pada {$dayName} ({$timeRange}).";
            
            if ($delegation->type === 'temporary' && $delegation->valid_until) {
                $notificationMessage .= " Berlaku hingga " . Carbon::parse($delegation->valid_until)->format('d M Y') . ".";
            } elseif ($delegation->type === 'permanent') {
                $notificationMessage .= " Tipe: Permanent.";
            }

            Notification::create([
                'user_id' => $validated['delegated_to_user_id'],
                'type' => 'delegation',
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'related_id' => $delegation->id,
                'related_type' => SessionDelegation::class,
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Delegasi berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Store multiple delegations at once
    private function storeMultipleDelegations(Request $request, array $timetableIds)
    {
        try {
            $validated = $request->validate([
                'timetable_ids' => 'required|array',
                'timetable_ids.*' => 'required|exists:timetables,id',
                'delegated_to_user_id' => 'required|exists:users,id',
                'type' => 'required|in:permanent,temporary',
                'valid_from' => 'nullable|date',
                'valid_until' => 'nullable|date|after:valid_from',
                'admin_notes' => 'nullable|string|max:500',
                'leave_request_id' => 'nullable|exists:teacher_leave_requests,id',
            ]);
            
            $delegatedUserId = $validated['delegated_to_user_id'];
            $delegatedUser = User::with('roles', 'teacher', 'student')->findOrFail($delegatedUserId);
            $delegatedRole = $delegatedUser->roles->first()->name ?? null;
            
            if (!in_array($delegatedRole, ['teacher', 'student'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delegasi hanya boleh kepada guru atau murid!'
                ], 400);
            }
            
            $delegations = [];
            $errors = [];
            
            foreach ($timetableIds as $timetableId) {
                try {
                    $timetable = Timetable::with([
                        'classSubject.subject',
                        'classSubject.class',
                        'classSubject.teacher.user'
                    ])->findOrFail($timetableId);
                    
                    if (!$timetable || !$timetable->classSubject) {
                        $errors[] = "Jadwal ID {$timetableId} tidak ditemukan atau tidak valid.";
                        continue;
                    }
                    
                    $originalTeacherId = $timetable->classSubject->teacher->user_id;
                    if (!$originalTeacherId) {
                        $errors[] = "Guru asli untuk jadwal ID {$timetableId} tidak ditemukan.";
                        continue;
                    }
                    
                    // Validate delegate role
                    if ($delegatedRole === 'teacher') {
                        $teacher = $delegatedUser->teacher;
                        if (!$teacher) {
                            $errors[] = "User yang dipilih bukan guru untuk jadwal ID {$timetableId}.";
                            continue;
                        }
                        
                        // Check if teacher has conflicting schedule
                        $conflictingSchedule = Timetable::whereHas('classSubject', function($q) use ($teacher) {
                            $q->where('teacher_id', $teacher->id);
                        })
                        ->where('day_of_week', $timetable->day_of_week)
                        ->where(function($query) use ($timetable) {
                            $query->whereBetween('start_time', [$timetable->start_time, $timetable->end_time])
                                  ->orWhereBetween('end_time', [$timetable->start_time, $timetable->end_time])
                                  ->orWhere(function($q) use ($timetable) {
                                      $q->where('start_time', '<=', $timetable->start_time)
                                        ->where('end_time', '>=', $timetable->end_time);
                                  });
                        })
                        ->where('id', '!=', $timetableId)
                        ->first();
                        
                        if ($conflictingSchedule) {
                            $errors[] = "Guru yang dipilih sedang mengajar di jam yang sama untuk jadwal ID {$timetableId}.";
                            continue;
                        }
                    }
                    
                    if ($delegatedRole === 'student') {
                        $student = $delegatedUser->student;
                        if (!$student || $student->class_id != $timetable->classSubject->class_id) {
                            $errors[] = "Murid yang dipilih bukan dari kelas yang sama dengan jadwal ID {$timetableId}.";
                            continue;
                        }
                    }
                    
                    // Create delegation
                    $delegation = SessionDelegation::create([
                        'timetable_id' => $timetableId,
                        'original_teacher_id' => $originalTeacherId,
                        'delegated_to_user_id' => $delegatedUserId,
                        'type' => $validated['type'],
                        'valid_from' => $validated['valid_from'] ?? now()->toDateString(),
                        'valid_until' => $validated['valid_until'] ?? null,
                        'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
                        'created_by' => Auth::id(),
                        'status' => 'active',
                    ]);
                    
                    $delegations[] = $delegation;
                    
                    // Create notification
                    $subjectName = $timetable->classSubject->subject->name ?? 'Mata Pelajaran';
                    $className = $timetable->classSubject->class->name ?? 'Kelas';
                    $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                    $dayName = $dayNames[$timetable->day_of_week] ?? 'Hari';
                    $timeRange = Carbon::parse($timetable->start_time)->format('H:i') . ' - ' . Carbon::parse($timetable->end_time)->format('H:i');
                    
                    Notification::create([
                        'user_id' => $delegatedUserId,
                        'type' => 'delegation',
                        'title' => 'Tugas Delegasi Baru',
                        'message' => "Anda mendapat tugas delegasi untuk mengajar {$subjectName} - {$className} pada {$dayName} ({$timeRange}).",
                        'related_id' => $delegation->id,
                        'related_type' => SessionDelegation::class,
                        'is_read' => false,
                    ]);
                } catch (\Exception $e) {
                    $errors[] = "Error untuk jadwal ID {$timetableId}: " . $e->getMessage();
                    \Log::error("Error creating delegation for timetable {$timetableId}: " . $e->getMessage());
                }
            }
            
            if (count($delegations) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada delegasi yang berhasil dibuat. ' . implode(' ', $errors)
                ], 400);
            }
            
            $message = count($delegations) . ' delegasi berhasil dibuat.';
            if (count($errors) > 0) {
                $message .= ' Beberapa delegasi gagal: ' . implode(' ', $errors);
            }
            
            // If this is from leave request, approve it
            if (isset($validated['leave_request_id'])) {
                $leaveRequest = TeacherLeaveRequest::find($validated['leave_request_id']);
                if ($leaveRequest && $leaveRequest->status === 'pending') {
                    $leaveRequest->update([
                        'status' => 'approved',
                        'substitute_user_id' => $delegatedUserId,
                        'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
                        'processed_by' => Auth::id(),
                        'processed_at' => now(),
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'delegations_created' => count($delegations),
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating multiple delegations: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat delegasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Store multiple delegations with different delegates per schedule
    private function storeMultipleDelegationsWithDifferentDelegates(Request $request, array $scheduleDelegations)
    {
        try {
            $validated = $request->validate([
                'schedule_delegations' => 'required|string', // JSON string
                'type' => 'required|in:permanent,temporary',
                'valid_from' => 'nullable|date',
                'valid_until' => 'nullable|date|after:valid_from',
                'admin_notes' => 'nullable|string|max:500',
                'leave_request_id' => 'nullable|exists:teacher_leave_requests,id',
            ]);
            
            // Validate each schedule delegation
            $delegations = [];
            $errors = [];
            
            foreach ($scheduleDelegations as $scheduleDelegation) {
                try {
                    // Support both timetable_id (single) and timetable_ids (array)
                    $timetableIds = [];
                    if (isset($scheduleDelegation['timetable_ids']) && is_array($scheduleDelegation['timetable_ids'])) {
                        $timetableIds = $scheduleDelegation['timetable_ids'];
                    } elseif (isset($scheduleDelegation['timetable_id'])) {
                        $timetableIds = [$scheduleDelegation['timetable_id']];
                    }
                    
                    if (empty($timetableIds) || !isset($scheduleDelegation['delegated_to_user_id'])) {
                        $errors[] = "Format data delegasi tidak valid.";
                        continue;
                    }
                    
                    $delegatedUserId = $scheduleDelegation['delegated_to_user_id'];
                    
                    // Process each timetable in the array
                    foreach ($timetableIds as $timetableId) {
                    
                        $timetable = Timetable::with([
                            'classSubject.subject',
                            'classSubject.class',
                            'classSubject.teacher.user'
                        ])->findOrFail($timetableId);
                        
                        if (!$timetable || !$timetable->classSubject) {
                            $errors[] = "Jadwal ID {$timetableId} tidak ditemukan atau tidak valid.";
                            continue;
                        }
                        
                        $originalTeacherId = $timetable->classSubject->teacher->user_id;
                        if (!$originalTeacherId) {
                            $errors[] = "Guru asli untuk jadwal ID {$timetableId} tidak ditemukan.";
                            continue;
                        }
                        
                        $delegatedUser = User::with('roles', 'teacher', 'student')->findOrFail($delegatedUserId);
                        $delegatedRole = $delegatedUser->roles->first()->name ?? null;
                        
                        if (!in_array($delegatedRole, ['teacher', 'student'])) {
                            $errors[] = "Delegasi untuk jadwal ID {$timetableId} hanya boleh kepada guru atau murid!";
                            continue;
                        }
                        
                        // Validate delegate role
                        if ($delegatedRole === 'teacher') {
                            $teacher = $delegatedUser->teacher;
                            if (!$teacher) {
                                $errors[] = "User yang dipilih bukan guru untuk jadwal ID {$timetableId}.";
                                continue;
                            }
                            
                            // Check if teacher has conflicting schedule
                            $conflictingSchedule = Timetable::whereHas('classSubject', function($q) use ($teacher) {
                                $q->where('teacher_id', $teacher->id);
                            })
                            ->where('day_of_week', $timetable->day_of_week)
                            ->where(function($query) use ($timetable) {
                                $query->whereBetween('start_time', [$timetable->start_time, $timetable->end_time])
                                      ->orWhereBetween('end_time', [$timetable->start_time, $timetable->end_time])
                                      ->orWhere(function($q) use ($timetable) {
                                          $q->where('start_time', '<=', $timetable->start_time)
                                            ->where('end_time', '>=', $timetable->end_time);
                                      });
                            })
                            ->where('id', '!=', $timetableId)
                            ->first();
                            
                            if ($conflictingSchedule) {
                                $errors[] = "Guru yang dipilih sedang mengajar di jam yang sama untuk jadwal ID {$timetableId}.";
                                continue;
                            }
                        }
                        
                        if ($delegatedRole === 'student') {
                            $student = $delegatedUser->student;
                            if (!$student || $student->class_id != $timetable->classSubject->class_id) {
                                $errors[] = "Murid yang dipilih bukan dari kelas yang sama dengan jadwal ID {$timetableId}.";
                                continue;
                            }
                        }
                        
                        // Create delegation
                        $delegation = SessionDelegation::create([
                            'timetable_id' => $timetableId,
                            'original_teacher_id' => $originalTeacherId,
                            'delegated_to_user_id' => $delegatedUserId,
                            'type' => $validated['type'],
                            'valid_from' => $validated['valid_from'] ?? now()->toDateString(),
                            'valid_until' => $validated['valid_until'] ?? null,
                            'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
                            'created_by' => Auth::id(),
                            'status' => 'active',
                        ]);
                        
                        $delegations[] = $delegation;
                        
                        // Create notification
                        $subjectName = $timetable->classSubject->subject->name ?? 'Mata Pelajaran';
                        $className = $timetable->classSubject->class->name ?? 'Kelas';
                        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        $dayName = $dayNames[$timetable->day_of_week] ?? 'Hari';
                        $timeRange = Carbon::parse($timetable->start_time)->format('H:i') . ' - ' . Carbon::parse($timetable->end_time)->format('H:i');
                        
                        Notification::create([
                            'user_id' => $delegatedUserId,
                            'type' => 'delegation',
                            'title' => 'Tugas Delegasi Baru',
                            'message' => "Anda mendapat tugas delegasi untuk mengajar {$subjectName} - {$className} pada {$dayName} ({$timeRange}).",
                            'related_id' => $delegation->id,
                            'related_type' => SessionDelegation::class,
                            'is_read' => false,
                        ]);
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error untuk jadwal: " . $e->getMessage();
                    \Log::error("Error creating delegation: " . $e->getMessage());
                }
            }
            
            if (count($delegations) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada delegasi yang berhasil dibuat. ' . implode(' ', $errors)
                ], 400);
            }
            
            $message = count($delegations) . ' delegasi berhasil dibuat.';
            if (count($errors) > 0) {
                $message .= ' Beberapa delegasi gagal: ' . implode(' ', $errors);
            }
            
            // If this is from leave request, approve it
            if (isset($validated['leave_request_id'])) {
                $leaveRequest = TeacherLeaveRequest::find($validated['leave_request_id']);
                if ($leaveRequest && $leaveRequest->status === 'pending') {
                    // Get unique substitute user IDs from all delegations created
                    $uniqueSubstituteIds = array_unique(array_map(function($del) {
                        return $del->delegated_to_user_id;
                    }, $delegations));
                    $primarySubstituteId = $uniqueSubstituteIds[0] ?? null;
                    
                    $leaveRequest->update([
                        'status' => 'approved',
                        'substitute_user_id' => $primarySubstituteId, // Use first substitute as primary
                        'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
                        'processed_by' => Auth::id(),
                        'processed_at' => now(),
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'delegations_created' => count($delegations),
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating multiple delegations with different delegates: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat delegasi: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update delegasi
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:permanent,temporary',
            'valid_until' => 'nullable|date',
            'admin_notes' => 'nullable|string|max:500',
            'status' => 'required|in:active,revoked',
        ]);

        $delegation = SessionDelegation::findOrFail($id);
        $delegation->update([
            'type' => $validated['type'],
            'valid_until' => $validated['valid_until'],
            'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delegasi berhasil diperbarui!'
        ]);
    }

    // Hapus delegasi
    public function destroy($id)
    {
        $delegation = SessionDelegation::findOrFail($id);
        $delegation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delegasi berhasil dihapus!'
        ]);
    }
    
    // Check if email exists
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $role = $user->roles->first()->name ?? 'Unknown';
            return response()->json([
                'exists' => true,
                'user_id' => $user->id,
                'message' => 'Email ditemukan: ' . $user->full_name . ' (' . $role . ')'
            ]);
        }
        
        return response()->json([
            'exists' => false,
            'message' => 'Email tidak terdaftar di sistem'
        ]);
    }

    /**
     * Approve teacher leave request and assign substitute
     */
    public function approveTeacherLeaveRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'substitute_user_id' => 'required|exists:users,id',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $leaveRequest = TeacherLeaveRequest::with([
            'timetable.classSubject',
            'teacher'
        ])->findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permohonan izin sudah diproses sebelumnya.'
            ], 400);
        }

        $substituteUser = User::with('roles')->findOrFail($validated['substitute_user_id']);
        $substituteRole = $substituteUser->roles->first()->name ?? null;

        // Validate substitute
        if (!in_array($substituteRole, ['guru', 'murid'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pengganti harus guru atau murid.'
            ], 400);
        }

        // If substitute is student, check if from same class
        if ($substituteRole === 'murid') {
            $student = $substituteUser->student;
            if (!$student || $student->class_id != $leaveRequest->timetable->classSubject->class_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Murid yang dipilih bukan dari kelas yang sama dengan jadwal.'
                ], 400);
            }
        }

        // If substitute is teacher, check if not teaching at the same time
        if ($substituteRole === 'guru') {
            $substituteTeacher = $substituteUser->teacher;
            if (!$substituteTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'User yang dipilih bukan guru.'
                ], 400);
            }

            // Check if teacher has class at the same time
            $conflictingTimetable = Timetable::whereHas('classSubject', function($query) use ($substituteTeacher, $leaveRequest) {
                $query->where('teacher_id', $substituteTeacher->user_id);
            })
            ->where('day_of_week', $leaveRequest->timetable->day_of_week)
            ->where(function($query) use ($leaveRequest) {
                $query->where(function($q) use ($leaveRequest) {
                    $q->where('start_time', '<=', $leaveRequest->timetable->start_time)
                      ->where('end_time', '>', $leaveRequest->timetable->start_time);
                })
                ->orWhere(function($q) use ($leaveRequest) {
                    $q->where('start_time', '<', $leaveRequest->timetable->end_time)
                      ->where('end_time', '>=', $leaveRequest->timetable->end_time);
                })
                ->orWhere(function($q) use ($leaveRequest) {
                    $q->where('start_time', '>=', $leaveRequest->timetable->start_time)
                      ->where('end_time', '<=', $leaveRequest->timetable->end_time);
                });
            })
            ->first();

            if ($conflictingTimetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru pengganti memiliki jadwal mengajar pada waktu yang sama.'
                ], 400);
            }
        }

        // Update leave request
        $leaveRequest->update([
            'status' => 'approved',
            'substitute_user_id' => $validated['substitute_user_id'],
            'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // Get all timetables from pivot table
        $leaveRequestTimetables = \DB::table('teacher_leave_request_timetables')
            ->where('teacher_leave_request_id', $leaveRequest->id)
            ->get();
        
        // Create delegations for each timetable-date combination
        $delegations = [];
        foreach ($leaveRequestTimetables as $leaveRequestTimetable) {
        $adminNotesText = !empty($validated['admin_notes']) ? $validated['admin_notes'] : '';
        $delegation = SessionDelegation::create([
                'timetable_id' => $leaveRequestTimetable->timetable_id,
            'original_teacher_id' => $leaveRequest->teacher_id,
            'delegated_to_user_id' => $validated['substitute_user_id'],
            'type' => 'temporary',
                'valid_from' => $leaveRequestTimetable->leave_date,
                'valid_until' => $leaveRequestTimetable->leave_date,
            'status' => 'active',
            'admin_notes' => $adminNotesText ? 'Pengganti untuk permohonan izin guru: ' . $adminNotesText : null,
            'created_by' => Auth::id(),
        ]);
            $delegations[] = $delegation;
        }

        // Send notification to teacher
        $startDate = Carbon::parse($leaveRequest->leave_date);
        $endDate = $leaveRequest->end_date ? Carbon::parse($leaveRequest->end_date) : $startDate;
        
        $dateRange = $startDate->format('d/m/Y');
        if ($endDate->gt($startDate)) {
            $dateRange .= ' - ' . $endDate->format('d/m/Y');
            $totalDays = $startDate->diffInDays($endDate) + 1;
            $dateRange .= ' (' . $totalDays . ' hari)';
        }
        
        Notification::create([
            'user_id' => $leaveRequest->teacher_id,
            'title' => 'Permohonan Izin Disetujui',
            'message' => 'Permohonan izin Anda untuk tanggal ' . $dateRange . ' telah disetujui. Pengganti: ' . $substituteUser->full_name . ' (' . count($delegations) . ' sesi)',
            'type' => 'leave_request_approved',
            'is_read' => false,
        ]);

        // Send notification to substitute
        $subjectNames = [];
        foreach ($leaveRequestTimetables as $leaveRequestTimetable) {
            $timetable = Timetable::with('classSubject.subject')->find($leaveRequestTimetable->timetable_id);
            if ($timetable && $timetable->classSubject) {
                $subjectNames[] = $timetable->classSubject->subject->name;
            }
        }
        $uniqueSubjects = array_unique($subjectNames);
        $subjectName = count($uniqueSubjects) > 1 ? implode(', ', $uniqueSubjects) : ($uniqueSubjects[0] ?? 'Mata Pelajaran');
        
        $message = 'Anda ditugaskan sebagai pengganti untuk ' . $subjectName . ' pada tanggal ' . $dateRange;
        if (count($delegations) > 1) {
            $message .= ' (' . count($delegations) . ' sesi)';
        }
        
        Notification::create([
            'user_id' => $validated['substitute_user_id'],
            'title' => 'Tugas Pengganti',
            'message' => $message,
            'type' => 'substitute_assigned',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan izin disetujui dan pengganti berhasil ditugaskan.'
        ]);
    }

    /**
     * Store delegation for all timetables of a class subject on a specific day
     * (Like attendance system - one delegation per subject, not per hour)
     */
    private function storeDelegationForClassSubject(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subjects,id',
            'day_of_week' => 'required|integer|min:1|max:7',
            'delegated_to_user_id' => 'required|exists:users,id',
            'type' => 'required|in:permanent,temporary',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        // Get class subject with relationships
        $classSubject = \App\Models\ClassSubject::with([
            'subject',
            'class',
            'teacher.user'
        ])->findOrFail($validated['class_subject_id']);

        // Get all timetables for this class subject on this day
        $timetables = Timetable::where('class_subject_id', $validated['class_subject_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->orderBy('start_time')
            ->get();

        if ($timetables->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal untuk mata pelajaran ini pada hari yang dipilih!'
            ], 400);
        }

        // Get original teacher
        $originalTeacherId = $classSubject->teacher->user_id;
        if (!$originalTeacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Guru asli untuk mata pelajaran ini tidak ditemukan!'
            ], 400);
        }

        // Validate delegate user
        $delegatedUser = User::with('roles', 'teacher', 'student')->find($validated['delegated_to_user_id']);
        if (!$delegatedUser) {
            return response()->json([
                'success' => false,
                'message' => 'Email delegasi tidak ditemukan!'
            ], 400);
        }

        $delegatedRole = $delegatedUser->roles->first()->name ?? null;

        // Validate delegate role
        if (!in_array($delegatedRole, ['guru', 'murid'])) {
            return response()->json([
                'success' => false,
                'message' => 'Delegasi hanya boleh kepada guru atau murid!'
            ], 400);
        }

        // Validate conflicts for all timetables
        foreach ($timetables as $timetable) {
            // If delegate is teacher, check for conflicts
            if ($delegatedRole === 'guru') {
                $teacher = $delegatedUser->teacher;
                if (!$teacher) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User yang dipilih bukan guru!'
                    ], 400);
                }

                $conflictingSchedule = Timetable::whereHas('classSubject', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->where('day_of_week', $timetable->day_of_week)
                ->where(function($query) use ($timetable) {
                    $query->whereBetween('start_time', [$timetable->start_time, $timetable->end_time])
                          ->orWhereBetween('end_time', [$timetable->start_time, $timetable->end_time])
                          ->orWhere(function($q) use ($timetable) {
                              $q->where('start_time', '<=', $timetable->start_time)
                                ->where('end_time', '>=', $timetable->end_time);
                          });
                })
                ->where('id', '!=', $timetable->id)
                ->first();

                if ($conflictingSchedule) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Guru yang dipilih sedang mengajar di jam yang sama dengan salah satu jadwal!'
                    ], 400);
                }
            }

            // If delegate is student, check if from same class
            if ($delegatedRole === 'murid') {
                $student = $delegatedUser->student;
                if (!$student) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User yang dipilih bukan murid!'
                    ], 400);
                }

                if ($student->class_id != $classSubject->class_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Murid yang dipilih bukan dari kelas yang sama dengan mata pelajaran!'
                    ], 400);
                }
            }
        }

        // Create delegations for all timetables
        $delegations = [];
        $earliestStart = $timetables->first()->start_time;
        $latestEnd = $timetables->last()->end_time;

        foreach ($timetables as $timetable) {
            // Check if delegation already exists
            $existingDelegation = SessionDelegation::where('timetable_id', $timetable->id)
                ->where('status', 'active')
                ->where(function($query) use ($validated) {
                    $query->where('type', 'permanent')
                          ->orWhere(function($q) use ($validated) {
                              $q->where('type', 'temporary')
                                ->where('valid_until', '>=', $validated['valid_from'] ?? now()->toDateString());
                          });
                })
                ->first();

            if ($existingDelegation) {
                continue; // Skip if already delegated
            }

            $delegation = SessionDelegation::create([
                'timetable_id' => $timetable->id,
                'original_teacher_id' => $originalTeacherId,
                'delegated_to_user_id' => $validated['delegated_to_user_id'],
                'type' => $validated['type'],
                'valid_from' => $validated['valid_from'] ?? now()->toDateString(),
                'valid_until' => $validated['valid_until'] ?? null,
                'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
                'created_by' => Auth::id(),
                'status' => 'active',
            ]);

            $delegations[] = $delegation;
        }

        if (empty($delegations)) {
            return response()->json([
                'success' => false,
                'message' => 'Semua jadwal untuk mata pelajaran ini sudah memiliki delegasi aktif!'
            ], 400);
        }

        // Create notification
        $subjectName = $classSubject->subject->name ?? 'Mata Pelajaran';
        $className = $classSubject->class->name ?? 'Kelas';
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $dayName = $dayNames[$validated['day_of_week']] ?? 'Hari';
        $timeRange = Carbon::parse($earliestStart)->format('H:i') . ' - ' . Carbon::parse($latestEnd)->format('H:i');
        
        $notificationTitle = 'Tugas Delegasi Baru';
        $notificationMessage = "Anda mendapat tugas delegasi untuk mengajar {$subjectName} - {$className} pada {$dayName} ({$timeRange}).";
        
        if ($validated['type'] === 'temporary' && $validated['valid_until']) {
            $notificationMessage .= " Berlaku hingga " . Carbon::parse($validated['valid_until'])->format('d M Y') . ".";
        } elseif ($validated['type'] === 'permanent') {
            $notificationMessage .= " Tipe: Permanent.";
        }

        Notification::create([
            'user_id' => $validated['delegated_to_user_id'],
            'type' => 'delegation',
            'title' => $notificationTitle,
            'message' => $notificationMessage,
            'related_id' => $delegations[0]->id,
            'related_type' => SessionDelegation::class,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delegasi berhasil ditambahkan untuk ' . count($delegations) . ' jadwal!'
        ]);
    }

    /**
     * Reject teacher leave request
     */
    public function rejectTeacherLeaveRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $leaveRequest = TeacherLeaveRequest::findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permohonan izin sudah diproses sebelumnya.'
            ], 400);
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_notes' => !empty($validated['admin_notes']) ? $validated['admin_notes'] : null,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // Send notification to teacher
        $dateRange = $leaveRequest->leave_date->format('d/m/Y');
        if ($leaveRequest->end_date && $leaveRequest->end_date->gt($leaveRequest->leave_date)) {
            $dateRange .= ' - ' . $leaveRequest->end_date->format('d/m/Y');
        }
        
        Notification::create([
            'user_id' => $leaveRequest->teacher_id,
            'title' => 'Permohonan Izin Ditolak',
            'message' => 'Permohonan izin Anda untuk tanggal ' . $dateRange . ' telah ditolak.',
            'type' => 'leave_request_rejected',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan izin ditolak.'
        ]);
    }

    /**
     * Show teacher leave request detail
     */
    public function showTeacherLeaveRequestDetail($id)
    {
        $leaveRequest = TeacherLeaveRequest::with([
            'teacher',
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetables.timetable.classSubject.subject',
            'timetables.timetable.classSubject.class',
            'substitute',
            'processedBy'
        ])->findOrFail($id);

        // Add document URL if exists
        if ($leaveRequest->supporting_document) {
            $leaveRequest->document_url = asset('storage/' . $leaveRequest->supporting_document);
        }

        // Transform data for frontend
        $data = $leaveRequest->toArray();
        
        // Include multiple timetables if available
        if ($leaveRequest->timetables && $leaveRequest->timetables->count() > 0) {
            // Get all existing delegations for this leave request's timetables
            $timetableIds = $leaveRequest->timetables->pluck('timetable_id')->toArray();
            $existingDelegations = SessionDelegation::whereIn('timetable_id', $timetableIds)
                ->where('status', 'active')
                ->pluck('timetable_id')
                ->toArray();
            
            $data['timetables'] = $leaveRequest->timetables->map(function($timetableRequest) use ($existingDelegations) {
                $timetable = $timetableRequest->timetable;
                $hasDelegation = in_array($timetable->id, $existingDelegations);
                
                return [
                    'id' => $timetable->id,
                    'date' => $timetableRequest->leave_date->format('Y-m-d'),
                    'day_of_week' => $timetable->day_of_week,
                    'start_time' => $timetable->start_time,
                    'end_time' => $timetable->end_time,
                    'class_subject' => [
                        'subject_id' => $timetable->classSubject->subject_id ?? null,
                        'subject' => [
                            'id' => $timetable->classSubject->subject->id ?? null,
                            'name' => $timetable->classSubject->subject->name ?? 'N/A'
                        ],
                        'class_id' => $timetable->classSubject->class_id ?? null,
                        'class' => [
                            'id' => $timetable->classSubject->class->id ?? null,
                            'name' => $timetable->classSubject->class->name ?? 'N/A'
                        ]
                    ],
                    'group_type' => $timetable->group_type,
                    'location_type' => $timetable->location_type,
                    'week_alternation' => $timetable->week_alternation,
                    'has_delegation' => $hasDelegation,
                ];
            })->toArray();
        }
        
        // Keep single timetable for backward compatibility
        $singleTimetableId = $leaveRequest->timetable->id;
        $hasSingleDelegation = SessionDelegation::where('timetable_id', $singleTimetableId)
            ->where('status', 'active')
            ->exists();
        
        $data['timetable'] = [
            'id' => $singleTimetableId,
            'day_of_week' => $leaveRequest->timetable->day_of_week,
            'start_time' => $leaveRequest->timetable->start_time,
            'end_time' => $leaveRequest->timetable->end_time,
            'class_subject' => [
                'subject_id' => $leaveRequest->timetable->classSubject->subject_id ?? null,
                'subject' => [
                    'id' => $leaveRequest->timetable->classSubject->subject->id ?? null,
                    'name' => $leaveRequest->timetable->classSubject->subject->name ?? 'N/A'
                ],
                'class_id' => $leaveRequest->timetable->classSubject->class_id ?? null,
                'class' => [
                    'id' => $leaveRequest->timetable->classSubject->class->id ?? null,
                    'name' => $leaveRequest->timetable->classSubject->class->name ?? 'N/A'
                ]
            ],
            'has_delegation' => $hasSingleDelegation
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
