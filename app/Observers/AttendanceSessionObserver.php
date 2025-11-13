<?php

namespace App\Observers;

use App\Models\AttendanceSession;
use App\Models\TeacherPresence;
use App\Models\TeacherLeaveRequest;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class AttendanceSessionObserver
{
    /**
     * Handle the AttendanceSession "created" event.
     * When teacher opens attendance session, automatically record teacher presence as "Hadir"
     */
    public function created(AttendanceSession $session): void
    {
        // Skip if this is a delegated session (opened by someone else)
        // Only record presence for the original teacher if they opened it themselves
        if ($session->is_delegated && $session->opened_by_user_id != $session->teacher_id) {
            // This is a delegated session, don't record presence for original teacher
            // The delegate will handle their own attendance
            return;
        }

        $teacherId = $session->teacher_id;
        $todayCarbon = TimeOverrideService::now();
        $today = $todayCarbon->toDateString();

        // Get all required timetables for this teacher today
        $carbonDayOfWeek = $todayCarbon->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday
        $dbDayOfWeek = $carbonDayOfWeek == 0 ? 7 : $carbonDayOfWeek;
        
        $requiredTimetables = \App\Models\Timetable::whereHas('classSubject', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where('day_of_week', $dbDayOfWeek)
            ->get();
        
        $totalRequiredSessions = $requiredTimetables->count();

        // Count how many sessions teacher has opened today (not delegated or opened by teacher themselves)
        $openedSessions = AttendanceSession::where('teacher_id', $teacherId)
            ->whereDate('created_at', $today)
            ->where(function($query) use ($teacherId) {
                $query->where('is_delegated', false)
                      ->orWhere('opened_by_user_id', $teacherId);
            })
            ->count();

        // Check if there's already a presence record for today
        $existingPresence = TeacherPresence::where('teacher_id', $teacherId)
            ->whereDate('date', $today)
            ->first();

        // If already exists, check if status is I/S (from leave request)
        // Don't override I/S status, only update if status is H or A
        if ($existingPresence) {
            // If status is already I/S (from leave request), don't change it
            if (in_array($existingPresence->status, ['I', 'S'])) {
                return;
            }

            // If status is H or A, update to H (Hadir) because teacher opened a session
            if (in_array($existingPresence->status, ['H', 'A'])) {
                // Update notes with session count information
                $notes = 'Hadir: Membuka ' . $openedSessions . ' dari ' . $totalRequiredSessions . ' sesi absensi';
                if ($openedSessions < $totalRequiredSessions) {
                    $notes .= ' (tidak lengkap)';
                }
                
                $existingPresence->update([
                    'status' => 'H',
                    'notes' => $notes
                ]);
                return;
            }
        }

        // Check if there's an approved leave request for today
        // If yes, don't create presence record (let TeacherLeaveRequestObserver handle it)
        $leaveRequest = TeacherLeaveRequest::where('teacher_id', $teacherId)
            ->where('status', 'approved')
            ->whereDate('leave_date', '<=', $today)
            ->where(function($query) use ($today) {
                $query->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', $today);
            })
            ->first();

        if ($leaveRequest) {
            // There's an approved leave request, don't create presence record here
            // TeacherLeaveRequestObserver will handle it
            return;
        }

        // Create new presence record as "Hadir" with session count information
        $notes = 'Hadir: Membuka ' . $openedSessions . ' dari ' . $totalRequiredSessions . ' sesi absensi';
        if ($openedSessions < $totalRequiredSessions) {
            $notes .= ' (tidak lengkap)';
        }
        
        TeacherPresence::create([
            'teacher_id' => $teacherId,
            'date' => $today,
            'status' => 'H',
            'check_in_time' => TimeOverrideService::now(),
            'notes' => $notes
        ]);

        \Log::info('Teacher presence automatically recorded from attendance session', [
            'teacher_id' => $teacherId,
            'session_id' => $session->id,
            'timetable_id' => $session->timetable_id,
            'date' => $today
        ]);
    }
}

