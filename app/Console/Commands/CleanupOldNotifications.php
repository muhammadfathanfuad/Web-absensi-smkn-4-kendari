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
    protected $signature = 'notifications:cleanup {--days=2 : Number of days to keep read notifications} {--unread-days=30 : Number of days to keep unread notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up read notifications older than specified days (default: 2 days) and unread notifications older than specified days (default: 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $unreadDays = (int) $this->option('unread-days');
        $cutoffDate = Carbon::now()->subDays($days);
        $unreadCutoffDate = Carbon::now()->subDays($unreadDays);
        
        $this->info("Starting cleanup of notifications:");
        $this->info("- Read notifications older than {$days} days (read before {$cutoffDate->format('Y-m-d H:i:s')})");
        $this->info("- Unread notifications older than {$unreadDays} days (created before {$unreadCutoffDate->format('Y-m-d H:i:s')})");
        
        try {
            // Get count before deletion for read notifications
            $readCountBefore = Notification::where('is_read', true)
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
            
            // Get count before deletion for unread notifications
            $unreadCountBefore = Notification::where('is_read', false)
                ->where('created_at', '<', $unreadCutoffDate)
                ->count();
            
            if ($readCountBefore === 0 && $unreadCountBefore === 0) {
                $this->info("No notifications found to delete.");
                return Command::SUCCESS;
            }
            
            // Delete read notifications older than cutoff date
            $deletedRead = Notification::where('is_read', true)
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
            
            // Delete unread notifications older than unread cutoff date
            $deletedUnread = Notification::where('is_read', false)
                ->where('created_at', '<', $unreadCutoffDate)
                ->delete();
            
            $totalDeleted = $deletedRead + $deletedUnread;
            
            $this->info("Cleanup completed successfully!");
            $this->info("- Read notifications deleted: {$deletedRead}");
            $this->info("- Unread notifications deleted: {$deletedUnread}");
            $this->info("- Total deleted: {$totalDeleted}");
            
            Log::info('Notification cleanup completed', [
                'read_days' => $days,
                'unread_days' => $unreadDays,
                'read_cutoff_date' => $cutoffDate->format('Y-m-d H:i:s'),
                'unread_cutoff_date' => $unreadCutoffDate->format('Y-m-d H:i:s'),
                'deleted_read_count' => $deletedRead,
                'deleted_unread_count' => $deletedUnread,
                'total_deleted' => $totalDeleted
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

