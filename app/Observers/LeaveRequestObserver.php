<?php

namespace App\Observers;

use App\Models\LeaveRequest;
use App\Models\StudentPresence;
use Carbon\Carbon;

class LeaveRequestObserver
{
    /**
     * Handle the LeaveRequest "updated" event.
     * When leave request is approved, record as Izin/Sakit in student_presences
     */
    public function updated(LeaveRequest $leaveRequest): void
    {
        // Check if there's any change in approval/rejection
        $originalApproved = $leaveRequest->getOriginal('approved_by_teachers') ?? [];
        $originalRejected = $leaveRequest->getOriginal('rejected_by_teachers') ?? [];
        $currentApproved = $leaveRequest->approved_by_teachers ?? [];
        $currentRejected = $leaveRequest->rejected_by_teachers ?? [];

        // Skip if no change in approval/rejection counts
        if (count($originalApproved) === count($currentApproved) && 
            count($originalRejected) === count($currentRejected)) {
            return;
        }

        $studentId = $leaveRequest->student_id;
        
        // Get approval and rejection counts
        $approvalCount = count($currentApproved);
        $rejectionCount = count($currentRejected);
        
        // Determine status based on leave type and approval/rejection
        $baseStatus = match($leaveRequest->leave_type) {
            'sakit' => 'S',
            'izin' => 'I',
            default => 'I'
        };

        // Determine final status:
        // - If all approve: Hadir (H)
        // - If all reject: Alfa (A)
        // - If mixed: base status (I/S) with approval_count and rejection_count
        $finalStatus = $baseStatus;
        if ($approvalCount > 0 && $rejectionCount === 0) {
            // All approve - status = Hadir
            $finalStatus = 'H';
        } elseif ($approvalCount === 0 && $rejectionCount > 0) {
            // All reject
            $finalStatus = 'A';
        } else {
            // Mixed - keep base status (I/S) with approval_count and rejection_count
            $finalStatus = $baseStatus;
        }

        // Create/update presence records for all dates in the leave request range
        $startDate = Carbon::parse($leaveRequest->start_date)->startOfDay();
        $endDate = $leaveRequest->end_date 
            ? Carbon::parse($leaveRequest->end_date)->startOfDay() 
            : $startDate->copy();

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $existing = StudentPresence::where('student_id', $studentId)
                ->whereDate('date', $currentDate)
                ->first();

            if ($existing) {
                // Update existing record
                $existing->update([
                    'status' => $finalStatus,
                    'approval_count' => $approvalCount,
                    'rejection_count' => $rejectionCount,
                    'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type)
                ]);
            } else {
                // Create new presence record
                StudentPresence::create([
                    'student_id' => $studentId,
                    'date' => $currentDate,
                    'status' => $finalStatus,
                    'approval_count' => $approvalCount,
                    'rejection_count' => $rejectionCount,
                    'check_in_time' => null,
                    'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type)
                ]);
            }

            $currentDate->addDay();
        }
    }
}
