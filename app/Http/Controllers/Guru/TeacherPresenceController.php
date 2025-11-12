<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TeacherPresence;
use App\Models\TeacherLeaveRequest;
use App\Models\SessionDelegation;
use App\Services\TimeOverrideService;

class TeacherPresenceController extends Controller
{
    /**
     * Store teacher presence record
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $teacher = $user->teacher;
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda bukan guru.'
                ], 403);
            }

            $teacherId = $teacher->user_id;
            $now = TimeOverrideService::now();
            $today = $now->copy()->startOfDay();
            $currentTime = $now->copy();

            // Validasi waktu: 07:00 - 14:45
            $startTime = $now->copy()->setTime(7, 0, 0);
            $endTime = $now->copy()->setTime(14, 45, 0);

            if ($currentTime->lt($startTime) || $currentTime->gt($endTime)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tombol kehadiran hanya bisa ditekan dari jam 07:00 sampai 14:45.'
                ], 400);
            }

            // Cek apakah sudah ada record untuk hari ini
            $existingPresence = TeacherPresence::where('teacher_id', $teacherId)
                ->whereDate('date', $today)
                ->first();

            if ($existingPresence) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mencatat kehadiran hari ini.'
                ], 400);
            }

            // Cek apakah ada permohonan izin yang disetujui untuk hari ini
            $leaveRequest = TeacherLeaveRequest::where('teacher_id', $teacherId)
                ->where('status', 'approved')
                ->whereDate('leave_date', '<=', $today)
                ->where(function($query) use ($today) {
                    $query->whereNull('end_date')
                          ->orWhereDate('end_date', '>=', $today);
                })
                ->first();

            if ($leaveRequest) {
                // Status berdasarkan jenis izin
                $status = match($leaveRequest->leave_type) {
                    'sakit' => 'S',
                    'izin' => 'I',
                    default => 'I'
                };

                TeacherPresence::create([
                    'teacher_id' => $teacherId,
                    'date' => $today,
                    'status' => $status,
                    'check_in_time' => $currentTime,
                    'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kehadiran dicatat sebagai ' . ($status === 'S' ? 'Sakit' : 'Izin') . ' berdasarkan permohonan izin.',
                    'status' => $status,
                    'status_display' => $status === 'S' ? 'Sakit' : 'Izin'
                ]);
            }

            // Cek apakah ada delegasi untuk hari ini
            $hasDelegation = SessionDelegation::where('original_teacher_id', $teacherId)
                ->where('status', 'active')
                ->where(function($query) use ($today) {
                    $query->where('type', 'permanent')
                          ->orWhere(function($q) use ($today) {
                              $q->where('type', 'temporary')
                                ->where('valid_from', '<=', $today)
                                ->where('valid_until', '>=', $today);
                          });
                })
                ->whereHas('timetable', function($q) use ($today) {
                    $q->where('day_of_week', $today->dayOfWeek);
                })
                ->exists();

            if ($hasDelegation) {
                TeacherPresence::create([
                    'teacher_id' => $teacherId,
                    'date' => $today,
                    'status' => 'I',
                    'check_in_time' => $currentTime,
                    'notes' => 'Izin: Ada delegasi untuk hari ini'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kehadiran dicatat sebagai Izin karena ada delegasi untuk hari ini.',
                    'status' => 'I',
                    'status_display' => 'Izin'
                ]);
            }

            // Jika tidak ada permohonan izin dan tidak ada delegasi, maka status = Hadir
            // (karena guru sudah menekan tombol kehadiran)
            $status = 'H';
            $message = 'Kehadiran dicatat sebagai Hadir.';

            TeacherPresence::create([
                'teacher_id' => $teacherId,
                'date' => $today,
                'status' => $status,
                'check_in_time' => $currentTime,
                'notes' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status,
                'status_display' => 'Hadir'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error storing teacher presence: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencatat kehadiran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's presence status
     */
    public function getTodayStatus()
    {
        try {
            $user = Auth::user();
            $teacher = $user->teacher;
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda bukan guru.'
                ], 403);
            }

            $teacherId = $teacher->user_id;
            $today = TimeOverrideService::now()->startOfDay();

            $presence = TeacherPresence::where('teacher_id', $teacherId)
                ->whereDate('date', $today)
                ->first();

            $now = TimeOverrideService::now();
            $currentTime = $now->copy();
            $startTime = $now->copy()->setTime(7, 0, 0);
            $endTime = $now->copy()->setTime(14, 45, 0);
            $isWithinTime = $currentTime->gte($startTime) && $currentTime->lte($endTime);

            return response()->json([
                'success' => true,
                'has_presence' => $presence !== null,
                'status' => $presence ? $presence->status : null,
                'status_display' => $presence ? $presence->status_display : null,
                'check_in_time' => $presence ? $presence->check_in_time : null,
                'is_within_time' => $isWithinTime,
                'current_time' => $currentTime->format('H:i:s')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting teacher presence status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil status kehadiran.'
            ], 500);
        }
    }
}
