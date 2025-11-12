<?php

namespace App\Observers;

use App\Models\TeacherLeaveRequest;
use App\Models\TeacherPresence;
use App\Services\TimeOverrideService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherLeaveRequestObserver
{
    /**
     * Handle the TeacherLeaveRequest "updated" event.
     * When teacher leave request is approved, automatically record teacher presence
     */
    public function updated(TeacherLeaveRequest $leaveRequest): void
    {
        // Only process if status changed to 'approved'
        $originalStatus = $leaveRequest->getOriginal('status');
        $currentStatus = $leaveRequest->status;

        if ($originalStatus === 'approved' || $currentStatus !== 'approved') {
            return; // Skip if already approved or not approved
        }

        $teacherId = $leaveRequest->teacher_id;
        $leaveDate = Carbon::parse($leaveRequest->leave_date)->startOfDay();
        $endDate = $leaveRequest->end_date 
            ? Carbon::parse($leaveRequest->end_date)->startOfDay() 
            : $leaveDate->copy();

        // Determine status based on leave type
        $status = match($leaveRequest->leave_type) {
            'sakit' => 'S',
            'izin' => 'I',
            default => 'I'
        };

        // Get all timetables from pivot table
        $leaveRequestTimetables = \DB::table('teacher_leave_request_timetables')
            ->where('teacher_leave_request_id', $leaveRequest->id)
            ->get();

        // Create/update presence records for all dates in the leave request range
        $currentDate = $leaveDate->copy();
        while ($currentDate->lte($endDate)) {
            // Check if there's a timetable for this date (based on day of week)
            // Carbon: 0=Sunday, 1=Monday, ..., 6=Saturday
            // Database: 1=Monday, 2=Tuesday, ..., 6=Saturday, 7=Sunday
            $carbonDayOfWeek = $currentDate->dayOfWeek; // 0-6
            $dbDayOfWeek = $carbonDayOfWeek == 0 ? 7 : $carbonDayOfWeek; // Convert to 1-7 format
            
            // Check if any timetable in the leave request matches this day of week
            $hasTimetableForDate = false;
            foreach ($leaveRequestTimetables as $leaveRequestTimetable) {
                $timetable = \App\Models\Timetable::find($leaveRequestTimetable->timetable_id);
                if ($timetable && $timetable->day_of_week == $dbDayOfWeek) {
                    $hasTimetableForDate = true;
                    break;
                }
            }

            // Only create presence record if there's a timetable for this day
            if ($hasTimetableForDate) {
                $existing = TeacherPresence::where('teacher_id', $teacherId)
                    ->whereDate('date', $currentDate)
                    ->first();

                if ($existing) {
                    // Update existing record if it's not already set to I/S
                    // Priority: Leave request (I/S) > Hadir (H) > Alfa (A)
                    if (!in_array($existing->status, ['I', 'S'])) {
                        $existing->update([
                            'status' => $status,
                            'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type) . ' (otomatis dari permohonan izin)'
                        ]);
                    }
                } else {
                    // Create new presence record
                    TeacherPresence::create([
                        'teacher_id' => $teacherId,
                        'date' => $currentDate,
                        'status' => $status,
                        'check_in_time' => null, // Tidak ada check-in karena otomatis
                        'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type) . ' (otomatis dari permohonan izin)'
                    ]);
                }
            }

            $currentDate->addDay();
        }

        \Log::info('Teacher presence automatically recorded from leave request approval', [
            'teacher_id' => $teacherId,
            'leave_request_id' => $leaveRequest->id,
            'status' => $status,
            'date_range' => $leaveDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')
        ]);
    }
}

