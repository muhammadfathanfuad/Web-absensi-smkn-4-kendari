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
     * NOTE: Manual presence recording (store method) has been removed.
     * Teacher presence is now automatically recorded when:
     * 1. Teacher opens attendance session (AttendanceSessionObserver)
     * 2. Teacher leave request is approved (TeacherLeaveRequestObserver)
     * 3. Teacher doesn't open any session (MarkTeacherAbsence command)
     */

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
