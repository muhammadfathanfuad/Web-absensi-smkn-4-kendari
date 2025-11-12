<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\StudentPresence;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class RecordStudentAbsence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:record-absence {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record student absence (Alfa) for students who did not scan QR code after 14:45';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') 
            ? Carbon::parse($this->option('date'))->startOfDay()
            : TimeOverrideService::now()->startOfDay();

        $this->info("Recording student absence for date: {$date->format('Y-m-d')}");

        // Get all students
        $students = Student::with('user')->get();
        $recorded = 0;
        $skipped = 0;

        foreach ($students as $student) {
            $studentId = $student->user_id;

            // Check if already has presence record
            $existing = StudentPresence::where('student_id', $studentId)
                ->whereDate('date', $date)
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            // Check for approved leave request
            $leaveRequest = LeaveRequest::where('student_id', $studentId)
                ->where(function($query) {
                    $query->where('status', 'approved')
                          ->orWhereIn('overall_status', ['approved', 'partially_approved']);
                })
                ->where(function($query) use ($date) {
                    $query->whereDate('start_date', '<=', $date)
                          ->where(function($q) use ($date) {
                              $q->whereNull('end_date')
                                ->orWhereDate('end_date', '>=', $date);
                          });
                })
                ->first();

            if ($leaveRequest) {
                // Status berdasarkan jenis izin
                $status = match($leaveRequest->leave_type) {
                    'sakit' => 'S',
                    'izin' => 'I',
                    default => 'I'
                };

                StudentPresence::create([
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => $status,
                    'check_in_time' => null,
                    'notes' => 'Izin: ' . ($leaveRequest->leave_type_display ?? $leaveRequest->leave_type)
                ]);
                $recorded++;
                continue;
            }

            // Check if student has scanned QR code
            $hasScanned = Attendance::where('student_id', $studentId)
                ->whereDate('created_at', $date)
                ->whereIn('status', ['H', 'T'])
                ->exists();

            if ($hasScanned) {
                // Should be handled by observer, but create if not exists
                $firstScan = Attendance::where('student_id', $studentId)
                    ->whereDate('created_at', $date)
                    ->whereIn('status', ['H', 'T'])
                    ->first();

                StudentPresence::create([
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => 'H',
                    'check_in_time' => $firstScan->check_in_time ? Carbon::parse($firstScan->check_in_time) : null,
                    'notes' => null
                ]);
                $recorded++;
            } else {
                // No scan = Alfa
                StudentPresence::create([
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => 'A',
                    'check_in_time' => null,
                    'notes' => 'Tidak ada scan QR code'
                ]);
                $recorded++;
            }
        }

        $this->info("Completed. Recorded: {$recorded}, Skipped: {$skipped}");
        return 0;
    }
}
