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
use Carbon\Carbon;

class DelegationController extends Controller
{
    // Menampilkan halaman manajemen delegasi
    public function index()
    {
        $delegations = SessionDelegation::with([
            'timetable.classSubject.subject',
            'timetable.classSubject.class',
            'timetable.classSubject.teacher.user',
            'originalTeacher.user',
            'delegatedTo',
            'createdBy'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
        
        $timetables = Timetable::with([
            'classSubject.subject',
            'classSubject.class',
            'classSubject.teacher.user'
        ])->get();
        
        // Get unique subjects
        $subjects = \App\Models\Subject::orderBy('name')->get();
        
        // Get unique classes
        $classes = \App\Models\Classroom::orderBy('name')->get();
        
        // Get teachers
        $teachers = Teacher::with('user')->get();
        
        $users = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['guru', 'murid']);
        })->with(['roles', 'teacher', 'student'])->get();
        
        return view('admin.delegasi', compact('delegations', 'timetables', 'users', 'subjects', 'classes', 'teachers'));
    }

    // Simpan delegasi baru
    public function store(Request $request)
    {
        try {
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
                'admin_notes' => $validated['admin_notes'],
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
            'admin_notes' => $validated['admin_notes'],
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
}
