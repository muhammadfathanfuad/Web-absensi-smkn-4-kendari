<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupOldActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activities:cleanup {--days=30 : Number of days to keep activities}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old activities from attendance_sessions and leave_requests tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Starting cleanup of activities older than {$days} days (before {$cutoffDate->format('Y-m-d H:i:s')})");
        
        try {
            // Cleanup attendance_sessions
            $attendanceSessionsDeleted = $this->cleanupAttendanceSessions($cutoffDate);
            
            // Cleanup leave_requests
            $leaveRequestsDeleted = $this->cleanupLeaveRequests($cutoffDate);
            
            $totalDeleted = $attendanceSessionsDeleted + $leaveRequestsDeleted;
            
            $this->info("Cleanup completed successfully!");
            $this->info("- Attendance sessions deleted: {$attendanceSessionsDeleted}");
            $this->info("- Leave requests deleted: {$leaveRequestsDeleted}");
            $this->info("- Total records deleted: {$totalDeleted}");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error during cleanup: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Clean up old attendance sessions
     */
    private function cleanupAttendanceSessions(Carbon $cutoffDate): int
    {
        $this->info("Cleaning up attendance sessions...");
        
        // Count records to be deleted
        $count = DB::table('attendance_sessions')
            ->where('created_at', '<', $cutoffDate)
            ->count();
            
        if ($count > 0) {
            // Delete old attendance sessions
            DB::table('attendance_sessions')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
                
            $this->info("Deleted {$count} old attendance sessions");
        } else {
            $this->info("No old attendance sessions to delete");
        }
        
        return $count;
    }
    
    /**
     * Clean up old leave requests
     */
    private function cleanupLeaveRequests(Carbon $cutoffDate): int
    {
        $this->info("Cleaning up leave requests...");
        
        // Count records to be deleted
        $count = DB::table('leave_requests')
            ->where('created_at', '<', $cutoffDate)
            ->count();
            
        if ($count > 0) {
            // Delete old leave requests
            DB::table('leave_requests')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
                
            $this->info("Deleted {$count} old leave requests");
        } else {
            $this->info("No old leave requests to delete");
        }
        
        return $count;
    }
}
