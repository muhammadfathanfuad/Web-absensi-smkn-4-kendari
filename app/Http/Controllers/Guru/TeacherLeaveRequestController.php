<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TeacherLeaveRequest;
use App\Models\Timetable;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class TeacherLeaveRequestController extends Controller
{
    /**
     * Display leave request form
     */
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Get teacher's timetables
        $timetables = Timetable::with([
            'classSubject.subject',
            'classSubject.class',
            'classSubject.teacher'
        ])
        ->whereHas('classSubject', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->user_id);
        })
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();

        // Get teacher's leave requests
        $leaveRequests = TeacherLeaveRequest::with([
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetables.timetable.classSubject.subject',
            'timetables.timetable.classSubject.class',
            'substitute',
            'processedBy'
        ])
        ->where('teacher_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('guru.permohonan-izin', compact('leaveRequests'));
    }

    /**
     * Get timetables for date range
     */
    public function getTimetablesForDateRange(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $validated['end_date'] ? Carbon::parse($validated['end_date']) : $startDate;
        
        // Get all timetables for this teacher
        $timetables = Timetable::with([
            'classSubject.subject',
            'classSubject.class',
            'classSubject.teacher'
        ])
        ->whereHas('classSubject', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->user_id);
        })
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();
        
        // Filter timetables based on date range and week type
        $filteredTimetables = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek == 0 ? 7 : $currentDate->dayOfWeek;
            $weekType = $this->getWeekTypeForDate($currentDate);
            
            foreach ($timetables as $timetable) {
                // Check if day of week matches
                if ($timetable->day_of_week != $dayOfWeek) {
                    continue;
                }
                
                // Check week alternation for XI classes
                if ($timetable->week_alternation) {
                    if ($timetable->week_alternation !== $weekType) {
                        continue;
                    }
                    
                    // Check group type and location type for XI classes
                    if ($timetable->group_type && $timetable->location_type) {
                        $class = $timetable->classSubject->class;
                        if ($class && $class->grade == 11) {
                            // Apply XI class logic
                            if ($weekType === 'ganjil') {
                                // Minggu ganjil: Kelompok A = Lab, Kelompok B = Teori
                                if ($timetable->group_type === 'A' && $timetable->location_type !== 'lab') {
                                    continue;
                                }
                                if ($timetable->group_type === 'B' && $timetable->location_type !== 'theory') {
                                    continue;
                                }
                            } else {
                                // Minggu genap: Kelompok A = Teori, Kelompok B = Lab
                                if ($timetable->group_type === 'A' && $timetable->location_type !== 'theory') {
                                    continue;
                                }
                                if ($timetable->group_type === 'B' && $timetable->location_type !== 'lab') {
                                    continue;
                                }
                            }
                        }
                    }
                }
                
                // Add to filtered list with specific date
                $key = $timetable->id . '_' . $currentDate->format('Y-m-d');
                if (!isset($filteredTimetables[$key])) {
                    $filteredTimetables[$key] = [
                        'timetable' => $timetable,
                        'date' => $currentDate->format('Y-m-d'),
                        'day_name' => $this->getDayName($dayOfWeek),
                    ];
                }
            }
            
            $currentDate->addDay();
        }
        
        // Format response
        $result = [];
        foreach ($filteredTimetables as $item) {
            $timetable = $item['timetable'];
            $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
            $dayName = $days[$timetable->day_of_week] ?? $timetable->day_of_week;
            
            $result[] = [
                'id' => $timetable->id,
                'date' => $item['date'],
                'day_name' => $dayName,
                'subject' => $timetable->classSubject->subject->name ?? 'N/A',
                'class' => $timetable->classSubject->class->name ?? 'N/A',
                'start_time' => Carbon::parse($timetable->start_time)->format('H:i'),
                'end_time' => Carbon::parse($timetable->end_time)->format('H:i'),
                'group_type' => $timetable->group_type,
                'location_type' => $timetable->location_type,
                'week_alternation' => $timetable->week_alternation,
            ];
        }
        
        return response()->json([
            'success' => true,
            'timetables' => $result
        ]);
    }
    
    /**
     * Get week type for date (ganjil/genap)
     */
    private function getWeekTypeForDate($date)
    {
        $dateCarbon = $date instanceof \Carbon\Carbon ? $date : Carbon::parse($date);
        $weekNumber = $dateCarbon->week;
        return ($weekNumber % 2 == 1) ? 'ganjil' : 'genap';
    }
    
    /**
     * Get day name
     */
    private function getDayName($dayOfWeek)
    {
        $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        return $days[$dayOfWeek] ?? 'N/A';
    }

    /**
     * Store leave request
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'error' => 'Data guru tidak ditemukan.',
                'message' => 'Data guru tidak ditemukan.'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'timetable_ids' => 'required|array|min:1',
                'timetable_ids.*' => 'required|string', // Format: timetable_id_date
            'leave_date' => 'required|date|after_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:leave_date',
            'leave_type' => 'required|in:sakit,izin,keperluan-keluarga,acara-keluarga,lainnya',
            'custom_leave_type' => 'nullable|string|max:100',
            'reason' => 'required|string|max:500',
            'dokumenPendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:512',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        }

        // Parse timetable_ids (format: timetable_id_date or comma-separated)
        // Format: timetable_id_YYYY-MM-DD or "timetable_id1_date1,timetable_id2_date2"
        $timetableData = [];
        $timetableIds = [];
        foreach ($validated['timetable_ids'] as $timetableIdDateString) {
            // Split by comma if multiple timetable_ids
            $timetableIdDateArray = explode(',', $timetableIdDateString);
            
            foreach ($timetableIdDateArray as $timetableIdDate) {
                $timetableIdDate = trim($timetableIdDate);
                
                // Find last underscore (before date)
                // Format: timetable_id_YYYY-MM-DD
                $lastUnderscore = strrpos($timetableIdDate, '_');
                if ($lastUnderscore !== false) {
                    $timetableId = substr($timetableIdDate, 0, $lastUnderscore);
                    $date = substr($timetableIdDate, $lastUnderscore + 1);
                    
                    // Validate date format (should be Y-m-d)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                        $timetableIds[] = $timetableId;
                        $timetableData[] = [
                            'timetable_id' => $timetableId,
                            'date' => $date
                        ];
                    }
                }
            }
        }
        
        if (empty($timetableData)) {
            return response()->json([
                'success' => false,
                'error' => 'Tidak ada jadwal yang dipilih.',
                'message' => 'Tidak ada jadwal yang dipilih.'
            ], 422);
        }

        // Check if all timetables belong to this teacher
        $timetables = Timetable::with('classSubject')
            ->whereIn('id', $timetableIds)
            ->get();
        
        foreach ($timetables as $timetable) {
        if ($timetable->classSubject->teacher_id !== $teacher->user_id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda tidak memiliki akses ke salah satu jadwal yang dipilih.',
                    'message' => 'Anda tidak memiliki akses ke salah satu jadwal yang dipilih.'
                ], 403);
            }
        }

        // Check for duplicate request (check if there's overlap with existing pending requests)
        // Check both main table and pivot table for overlaps
        $startDate = Carbon::parse($validated['leave_date']);
        $endDate = $validated['end_date'] ? Carbon::parse($validated['end_date']) : $startDate;
        
        // Check overlap for each timetable-date combination
        foreach ($timetableData as $data) {
            $timetableId = $data['timetable_id'];
            $leaveDate = Carbon::parse($data['date']);
            
            // Check in pivot table first (for new multi-timetable requests)
            // This is more accurate as it checks exact timetable_id and leave_date combination
            // Check if there's already a pending request with the same timetable_id and leave_date
            $existingPivot = DB::table('teacher_leave_request_timetables')
                ->join('teacher_leave_requests', 'teacher_leave_request_timetables.teacher_leave_request_id', '=', 'teacher_leave_requests.id')
                ->where('teacher_leave_requests.teacher_id', $user->id)
                ->where('teacher_leave_request_timetables.timetable_id', $timetableId)
                ->where('teacher_leave_request_timetables.leave_date', $leaveDate->format('Y-m-d'))
                ->where('teacher_leave_requests.status', 'pending')
                ->exists();
            
            if ($existingPivot) {
                Log::warning('Duplicate leave request detected in pivot table', [
                    'teacher_id' => $user->id,
                    'timetable_id' => $timetableId,
                    'leave_date' => $leaveDate->format('Y-m-d')
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Anda sudah mengajukan permohonan izin untuk salah satu jadwal pada periode yang sama.',
                    'message' => 'Anda sudah mengajukan permohonan izin untuk salah satu jadwal pada periode yang sama.'
                ], 422);
            }
            
            // Check in main table (for backward compatibility with old single-timetable requests)
            // Check if there's an overlap in the date range
            // For main table, we check if the leave_date falls within the existing request's date range
        $existingRequest = TeacherLeaveRequest::where('teacher_id', $user->id)
                ->where('timetable_id', $timetableId)
            ->where('status', 'pending')
                ->where(function($query) use ($leaveDate) {
                    $query->where(function($q) use ($leaveDate) {
                        // Check if leave_date falls within the existing request's date range
                        // Case 1: leave_date is between leave_date and end_date (or leave_date if end_date is null)
                        $q->where('leave_date', '<=', $leaveDate->format('Y-m-d'))
                          ->where(function($q2) use ($leaveDate) {
                              $q2->where('end_date', '>=', $leaveDate->format('Y-m-d'))
                                 ->orWhere(function($q3) use ($leaveDate) {
                                     $q3->whereNull('end_date')
                                        ->where('leave_date', '=', $leaveDate->format('Y-m-d'));
                                 });
                          });
                    })
                    ->orWhere(function($q) use ($leaveDate) {
                        // Case 2: existing request's leave_date is within the new request's date range
                        // This handles the case where existing request starts after the new leave_date
                        // but we need to check if it overlaps
                        $q->where('leave_date', '=', $leaveDate->format('Y-m-d'));
                    });
                })
            ->first();

        if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda sudah mengajukan permohonan izin untuk salah satu jadwal pada periode yang sama.',
                    'message' => 'Anda sudah mengajukan permohonan izin untuk salah satu jadwal pada periode yang sama.'
                ], 422);
        }
        }

        // Use database transaction to ensure data consistency
        return DB::transaction(function () use ($request, $user, $validated, $timetableIds, $timetableData) {
        try {
            // Handle file upload
            $supportingDocument = null;
            if ($request->hasFile('dokumenPendukung')) {
                $file = $request->file('dokumenPendukung');
                $filename = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
                $supportingDocument = $file->storeAs('teacher_leave_requests', $filename, 'public');
            }

                // Create leave request (use first timetable_id for backward compatibility)
            $leaveRequest = TeacherLeaveRequest::create([
                'teacher_id' => $user->id,
                    'timetable_id' => $timetableIds[0], // First timetable for backward compatibility
                'leave_date' => $validated['leave_date'],
                    'end_date' => $validated['end_date'] ?? null,
                'leave_type' => $validated['leave_type'],
                'custom_leave_type' => $validated['leave_type'] === 'lainnya' ? $validated['custom_leave_type'] : null,
                'reason' => $validated['reason'],
                'supporting_document' => $supportingDocument,
                'status' => 'pending',
            ]);
                
                // Create pivot records for all timetables
                // Note: We don't need to check for duplicates here because we already checked before the transaction
                // The unique constraint in the database will prevent duplicates within the same request
                foreach ($timetableData as $data) {
                    DB::table('teacher_leave_request_timetables')->insert([
                        'teacher_leave_request_id' => $leaveRequest->id,
                        'timetable_id' => $data['timetable_id'],
                        'leave_date' => $data['date'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

            Log::info('Teacher leave request created', [
                'leave_request_id' => $leaveRequest->id,
                'teacher_id' => $user->id,
                    'timetable_ids' => $timetableIds,
                    'timetable_count' => count($timetableIds),
                    'leave_date' => $validated['leave_date'],
                    'end_date' => $validated['end_date'] ?? null
            ]);

            // Send notification to all admin users
            $this->notifyAdminsAboutNewLeaveRequest($leaveRequest, $user);

                // Return success response with explicit status code 200
            return response()->json([
                'success' => true,
                'message' => 'Permohonan izin berhasil diajukan dan akan diproses oleh admin.',
                'data' => $leaveRequest
                ], 200);
            } catch (\Illuminate\Database\QueryException $e) {
                // Check if it's a duplicate entry error (should not happen if duplicate check works correctly)
                if ($e->getCode() == 23000) {
                    // Rollback transaction
                    DB::rollBack();
                    Log::warning('Duplicate entry detected in teacher leave request (should have been caught earlier)', [
                        'teacher_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    return response()->json([
                        'success' => false,
                        'error' => 'Anda sudah mengajukan permohonan izin untuk salah satu jadwal pada periode yang sama.',
                        'message' => 'Anda sudah mengajukan permohonan izin untuk salah satu jadwal pada periode yang sama.'
                    ], 422);
                }
                // Re-throw if it's not a duplicate error
                Log::error('Database error creating teacher leave request: ' . $e->getMessage(), [
                    'teacher_id' => $user->id,
                    'trace' => $e->getTraceAsString()
                ]);
                // Transaction will be rolled back automatically
                return response()->json([
                    'success' => false,
                    'error' => 'Terjadi kesalahan saat mengajukan permohonan izin.',
                    'message' => 'Terjadi kesalahan saat mengajukan permohonan izin.'
                ], 500);
        } catch (\Exception $e) {
            Log::error('Error creating teacher leave request: ' . $e->getMessage(), [
                'teacher_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
                // Transaction will be rolled back automatically
                return response()->json([
                    'success' => false,
                    'error' => 'Terjadi kesalahan saat mengajukan permohonan izin.',
                    'message' => 'Terjadi kesalahan saat mengajukan permohonan izin.'
                ], 500);
        }
        });
    }

    /**
     * Show leave request details
     */
    public function show($id)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        $leaveRequest = TeacherLeaveRequest::with([
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetable.classSubject.teacher',
            'timetables.timetable.classSubject.subject',
            'timetables.timetable.classSubject.class',
            'substitute',
            'processedBy'
        ])
        ->where('teacher_id', $user->id)
        ->findOrFail($id);

        // Add document URL if exists
        if ($leaveRequest->supporting_document) {
            $leaveRequest->document_url = asset('storage/' . $leaveRequest->supporting_document);
        }

        // Get all timetables
        $allTimetables = collect();
        if ($leaveRequest->timetables && $leaveRequest->timetables->count() > 0) {
            foreach ($leaveRequest->timetables as $timetablePivot) {
                if ($timetablePivot->timetable) {
                    $allTimetables->push([
                        'timetable' => $timetablePivot->timetable,
                        'leave_date' => $timetablePivot->leave_date
                    ]);
                }
            }
        } else {
            if ($leaveRequest->timetable) {
                $allTimetables->push([
                    'timetable' => $leaveRequest->timetable,
                    'leave_date' => $leaveRequest->leave_date
                ]);
            }
        }

        // Group timetables by day and class, then merge time ranges
        $groupedTimetables = [];
        foreach ($allTimetables as $item) {
            $timetable = $item['timetable'];
            $key = $timetable->day_of_week . '_' . $timetable->classSubject->class->name;
            
            if (!isset($groupedTimetables[$key])) {
                $groupedTimetables[$key] = [
                    'day_of_week' => $timetable->day_of_week,
                    'class_name' => $timetable->classSubject->class->name,
                    'subject_names' => collect(),
                    'start_times' => collect(),
                    'end_times' => collect(),
                ];
            }
            
            $groupedTimetables[$key]['subject_names']->push($timetable->classSubject->subject->name);
            $groupedTimetables[$key]['start_times']->push($timetable->start_time);
            $groupedTimetables[$key]['end_times']->push($timetable->end_time);
        }

        // Process grouped timetables
        $processedTimetables = [];
        foreach ($groupedTimetables as $group) {
            $processedTimetables[] = [
                'day_of_week' => $group['day_of_week'],
                'class_name' => $group['class_name'],
                'subjects' => $group['subject_names']->unique()->values()->toArray(),
                'start_time' => $group['start_times']->min(),
                'end_time' => $group['end_times']->max(),
            ];
        }

        // Transform data for frontend
        $data = $leaveRequest->toArray();
        $data['timetables'] = $processedTimetables;
        
        // Keep single timetable for backward compatibility
        if ($leaveRequest->timetable) {
        $data['timetable'] = [
            'id' => $leaveRequest->timetable->id,
            'day_of_week' => $leaveRequest->timetable->day_of_week,
            'start_time' => $leaveRequest->timetable->start_time,
            'end_time' => $leaveRequest->timetable->end_time,
            'class_subject' => [
                'subject' => [
                    'name' => $leaveRequest->timetable->classSubject->subject->name
                ],
                'class' => [
                    'name' => $leaveRequest->timetable->classSubject->class->name
                ]
            ]
        ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Notify all admin users about new leave request
     */
    private function notifyAdminsAboutNewLeaveRequest(TeacherLeaveRequest $leaveRequest, $teacher)
    {
        try {
            // Get all admin users
            $adminUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->where('status', 'active')->get();

            if ($adminUsers->isEmpty()) {
                Log::warning('No admin users found to notify about leave request', [
                    'leave_request_id' => $leaveRequest->id
                ]);
                return;
            }

            // Prepare notification message
            $teacherName = $teacher->full_name ?? 'Guru';
            $dateRange = Carbon::parse($leaveRequest->leave_date)->format('d/m/Y');
            if ($leaveRequest->end_date && $leaveRequest->end_date != $leaveRequest->leave_date) {
                $endDate = Carbon::parse($leaveRequest->end_date)->format('d/m/Y');
                $totalDays = Carbon::parse($leaveRequest->leave_date)->diffInDays(Carbon::parse($leaveRequest->end_date)) + 1;
                $dateRange .= ' - ' . $endDate . ' (' . $totalDays . ' hari)';
            }

            $notifications = [];
            foreach ($adminUsers as $admin) {
                $notifications[] = [
                    'user_id' => $admin->id,
                    'type' => 'leave_request',
                    'title' => 'Permohonan Izin Baru',
                    'message' => $teacherName . ' mengajukan permohonan izin untuk tanggal ' . $dateRange . '. Silakan reload halaman untuk melihat permintaan izin terbaru.',
                    'related_id' => $leaveRequest->id,
                    'related_type' => TeacherLeaveRequest::class,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert notifications
            if (!empty($notifications)) {
                Notification::insert($notifications);
                Log::info('Leave request notifications sent to admins', [
                    'leave_request_id' => $leaveRequest->id,
                    'admin_count' => count($notifications)
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send leave request notifications to admins', [
                'leave_request_id' => $leaveRequest->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
