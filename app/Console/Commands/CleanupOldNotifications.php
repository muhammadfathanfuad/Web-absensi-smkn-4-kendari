<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--days=2 : Number of days to keep read notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up read notifications older than specified days (default: 2 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Starting cleanup of read notifications older than {$days} days (read before {$cutoffDate->format('Y-m-d H:i:s')})");
        
        try {
            // Get count before deletion
            // Delete notifications that are read AND:
            // 1. Have read_at timestamp older than cutoff date, OR
            // 2. Don't have read_at but were created before cutoff date (legacy data)
            $countBefore = Notification::where('is_read', true)
                ->where(function($query) use ($cutoffDate) {
                    $query->where(function($q) use ($cutoffDate) {
                        // Has read_at and it's older than cutoff
                        $q->whereNotNull('read_at')
                          ->where('read_at', '<', $cutoffDate);
                    })->orWhere(function($q) use ($cutoffDate) {
                        // No read_at but created before cutoff (legacy data)
                        $q->whereNull('read_at')
                          ->where('created_at', '<', $cutoffDate);
                    });
                })
                ->count();
            
            if ($countBefore === 0) {
                $this->info("No read notifications found to delete.");
                return Command::SUCCESS;
            }
            
            // Delete read notifications older than cutoff date
            $deleted = Notification::where('is_read', true)
                ->where(function($query) use ($cutoffDate) {
                    $query->where(function($q) use ($cutoffDate) {
                        // Has read_at and it's older than cutoff
                        $q->whereNotNull('read_at')
                          ->where('read_at', '<', $cutoffDate);
                    })->orWhere(function($q) use ($cutoffDate) {
                        // No read_at but created before cutoff (legacy data)
                        $q->whereNull('read_at')
                          ->where('created_at', '<', $cutoffDate);
                    });
                })
                ->delete();
            
            $this->info("Cleanup completed successfully!");
            $this->info("- Read notifications deleted: {$deleted}");
            
            Log::info('Notification cleanup completed', [
                'days' => $days,
                'cutoff_date' => $cutoffDate->format('Y-m-d H:i:s'),
                'deleted_count' => $deleted
            ]);
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error during cleanup: " . $e->getMessage());
            Log::error('Notification cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
}

