<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\StudentPresence;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     * When student scans QR code, record as "Hadir" in student_presences
     */
    public function created(Attendance $attendance): void
    {
        // Only process if status is H (Hadir) or T (Terlambat) - both count as present
        if (!in_array($attendance->status, ['H', 'T'])) {
            return;
        }

        $studentId = $attendance->student_id;
        $attendanceDate = Carbon::parse($attendance->created_at)->startOfDay();

        // Check if already exists
        $existing = StudentPresence::where('student_id', $studentId)
            ->whereDate('date', $attendanceDate)
            ->first();

        if ($existing) {
            // Update to Hadir if not already Izin/Sakit (from leave request)
            if (!in_array($existing->status, ['I', 'S'])) {
                $existing->update([
                    'status' => 'H',
                    'check_in_time' => $attendance->check_in_time ? Carbon::parse($attendance->check_in_time) : null,
                ]);
            }
        } else {
            // Create new presence record
            StudentPresence::create([
                'student_id' => $studentId,
                'date' => $attendanceDate,
                'status' => 'H',
                'check_in_time' => $attendance->check_in_time ? Carbon::parse($attendance->check_in_time) : null,
                'notes' => null
            ]);
        }
    }
}
