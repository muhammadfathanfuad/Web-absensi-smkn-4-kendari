<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\TeacherPresence;
use App\Models\TeacherLeaveRequest;
use App\Models\AttendanceSession;
use App\Services\TimeOverrideService;
use Carbon\Carbon;

class MarkTeacherAbsence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-teacher-absence {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark teachers as absent (Alfa) if they did not open any attendance session on their teaching day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') 
            ? Carbon::parse($this->argument('date'))->startOfDay()
            : TimeOverrideService::today();

        $this->info("Checking teacher absence for date: {$date->format('Y-m-d')}");

        // Get day of week (1=Monday, 7=Sunday)
        $carbonDayOfWeek = $date->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday
        $dbDayOfWeek = $carbonDayOfWeek == 0 ? 7 : $carbonDayOfWeek;

        // Get all teachers who have timetables on this day
        $timetables = Timetable::with('classSubject.teacher')
            ->where('day_of_week', $dbDayOfWeek)
            ->where('is_active', true)
            ->get();

        // Get unique teacher IDs who should teach today
        $teacherIds = $timetables->pluck('classSubject.teacher.user_id')
            ->filter()
            ->unique()
            ->values();

        $this->info("Found {$teacherIds->count()} teachers with schedules today");

        $markedCount = 0;
        $skippedCount = 0;

        foreach ($teacherIds as $teacherId) {
            // Get all required timetables for this teacher on this day
            $teacherTimetables = $timetables->filter(function($timetable) use ($teacherId) {
                return $timetable->classSubject && $timetable->classSubject->teacher && 
                       $timetable->classSubject->teacher->user_id == $teacherId;
            });
            
            $totalRequiredSessions = $teacherTimetables->count();
            
            if ($totalRequiredSessions == 0) {
                // No schedule for this teacher today, skip
                $skippedCount++;
                continue;
            }

            // Check if teacher already has presence record
            $existingPresence = TeacherPresence::where('teacher_id', $teacherId)
                ->whereDate('date', $date)
                ->first();

            if ($existingPresence) {
                // Already has presence record, but let's check if we need to update notes
                // Count opened sessions (not delegated or opened by teacher themselves)
                $openedSessions = AttendanceSession::where('teacher_id', $teacherId)
                    ->whereDate('created_at', $date)
                    ->where(function($query) use ($teacherId) {
                        $query->where('is_delegated', false)
                              ->orWhere('opened_by_user_id', $teacherId);
                    })
                    ->count();
                
                // Update notes if status is H and session count is incomplete
                if ($existingPresence->status === 'H' && $openedSessions < $totalRequiredSessions) {
                    $notes = 'Hadir: Membuka ' . $openedSessions . ' dari ' . $totalRequiredSessions . ' sesi absensi (tidak lengkap)';
                    if ($existingPresence->notes !== $notes) {
                        $existingPresence->update(['notes' => $notes]);
                        $this->line("Updated notes for teacher {$teacherId}: {$openedSessions}/{$totalRequiredSessions} sessions");
                    }
                }
                
                $skippedCount++;
                continue;
            }

            // Check if teacher has approved leave request for this date
            $leaveRequest = TeacherLeaveRequest::where('teacher_id', $teacherId)
                ->where('status', 'approved')
                ->whereDate('leave_date', '<=', $date)
                ->where(function($query) use ($date) {
                    $query->whereNull('end_date')
                          ->orWhereDate('end_date', '>=', $date);
                })
                ->first();

            if ($leaveRequest) {
                // Has approved leave request, skip (TeacherLeaveRequestObserver should handle it)
                $skippedCount++;
                continue;
            }

            // Count how many sessions teacher opened today (not delegated or opened by teacher themselves)
            $openedSessions = AttendanceSession::where('teacher_id', $teacherId)
                ->whereDate('created_at', $date)
                ->where(function($query) use ($teacherId) {
                    $query->where('is_delegated', false)
                          ->orWhere('opened_by_user_id', $teacherId);
                })
                ->count();

            if ($openedSessions > 0) {
                // Teacher opened at least one session, should be marked as Hadir by AttendanceSessionObserver
                // But if somehow it wasn't recorded, create it now with session count
                $notes = 'Hadir: Membuka ' . $openedSessions . ' dari ' . $totalRequiredSessions . ' sesi absensi';
                if ($openedSessions < $totalRequiredSessions) {
                    $notes .= ' (tidak lengkap)';
                }
                
                TeacherPresence::create([
                    'teacher_id' => $teacherId,
                    'date' => $date,
                    'status' => 'H',
                    'check_in_time' => null,
                    'notes' => $notes
                ]);
                
                $this->line("Created presence record for teacher {$teacherId}: {$openedSessions}/{$totalRequiredSessions} sessions");
                $skippedCount++;
                continue;
            }

            // Teacher did not open any session and has no leave request
            // Mark as Alfa (Absent)
            TeacherPresence::create([
                'teacher_id' => $teacherId,
                'date' => $date,
                'status' => 'A',
                'check_in_time' => null,
                'notes' => 'Alfa: Tidak membuka sesi absensi di hari mengajar (' . $totalRequiredSessions . ' sesi wajib)'
            ]);

            $markedCount++;
            $this->line("Marked teacher {$teacherId} as absent ({$totalRequiredSessions} sessions required)");
        }

        $this->info("Completed: {$markedCount} teachers marked as absent, {$skippedCount} skipped");
        
        return Command::SUCCESS;
    }
}

