<?php

namespace App\Services;

use App\Models\StudentPresence;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use App\Services\TimeOverrideService;

class StudentPresenceService
{
    /**
     * Determine and record student presence for a specific date
     */
    public static function determineAndRecordPresence($studentId, $date = null)
    {
        if (!$date) {
            $date = TimeOverrideService::now()->startOfDay();
        } else {
            $date = Carbon::parse($date)->startOfDay();
        }

        // Check if already exists
        $existing = StudentPresence::where('student_id', $studentId)
            ->whereDate('date', $date)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Check for leave request (approved, rejected, or partially_approved)
        $leaveRequest = LeaveRequest::where('student_id', $studentId)
            ->whereIn('overall_status', ['approved', 'rejected', 'partially_approved'])
            ->where(function($query) use ($date) {
                $query->whereDate('start_date', '<=', $date)
                      ->where(function($q) use ($date) {
                          $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', $date);
                      });
            })
            ->first();

        if ($leaveRequest) {
            $approvalCount = count($leaveRequest->approved_by_teachers ?? []);
            $rejectionCount = count($leaveRequest->rejected_by_teachers ?? []);
            
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

            return StudentPresence::create([
                'student_id' => $studentId,
                'date' => $date,
                'status' => $finalStatus,
                'approval_count' => $approvalCount,
                'rejection_count' => $rejectionCount,
                'check_in_time' => null,
                'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type)
            ]);
        }

        // Check if student has scanned QR code (has Attendance record with H or T status)
        $hasScanned = Attendance::where('student_id', $studentId)
            ->whereDate('created_at', $date)
            ->whereIn('status', ['H', 'T'])
            ->first();

        if ($hasScanned) {
            return StudentPresence::create([
                'student_id' => $studentId,
                'date' => $date,
                'status' => 'H',
                'check_in_time' => $hasScanned->check_in_time ? Carbon::parse($hasScanned->check_in_time) : null,
                'notes' => null
            ]);
        }

        // For past dates (not today), if no scan and no leave request = Alfa
        if ($date->isPast() && !$date->isToday()) {
            return StudentPresence::create([
                'student_id' => $studentId,
                'date' => $date,
                'status' => 'A',
                'check_in_time' => null,
                'notes' => 'Tidak ada scan QR code'
            ]);
        }

        // For today: check if it's past 14:45
        if ($date->isToday()) {
            $now = TimeOverrideService::now();
            $cutoffTime = $now->copy()->setTime(14, 45, 0);
            
            if ($now->gt($cutoffTime)) {
                // Already past 14:45 and no scan = Alfa
                return StudentPresence::create([
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => 'A',
                    'check_in_time' => null,
                    'notes' => 'Tidak ada scan QR code'
                ]);
            }
        }

        // For future dates or current date before 14:45, return null (not determined yet)
        return null;
    }

    /**
     * Get student presence status for a date range
     */
    public static function getPresenceStatusForRange($studentId, $dateFrom, $dateTo)
    {
        $dateFromCarbon = Carbon::parse($dateFrom)->startOfDay();
        $dateToCarbon = Carbon::parse($dateTo)->endOfDay();

        // Get all presences in range
        $presences = StudentPresence::where('student_id', $studentId)
            ->whereBetween('date', [$dateFromCarbon, $dateToCarbon])
            ->get();

        // For dates without presence record, determine status
        $currentDate = $dateFromCarbon->copy();
        while ($currentDate->lte($dateToCarbon)) {
            $hasPresence = $presences->firstWhere('date', $currentDate->toDateString());
            
            if (!$hasPresence) {
                // Try to determine and record
                self::determineAndRecordPresence($studentId, $currentDate);
            }
            
            $currentDate->addDay();
        }

        // Get updated presences
        return StudentPresence::where('student_id', $studentId)
            ->whereBetween('date', [$dateFromCarbon, $dateToCarbon])
            ->get();
    }
}

