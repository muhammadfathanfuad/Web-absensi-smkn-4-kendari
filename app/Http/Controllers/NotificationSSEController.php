<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationSSEController extends Controller
{
    /**
     * Stream notifications via Server-Sent Events (SSE)
     */
    public function stream(Request $request)
    {
        // Check authentication first
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Get user ID and other necessary data BEFORE closing session
        $userId = Auth::id();
        $lastNotificationId = (int) $request->input('last_id', 0);
        
        // IMPORTANT: Close session immediately after reading data
        // This prevents session lock from blocking other requests
        session_write_close();
        
        // Disable timeout for long-running connection
        @set_time_limit(0);
        
        // Disable output buffering
        while (@ob_get_level() > 0) {
            @ob_end_clean();
        }
        
        // Set headers for SSE
        return response()->stream(function () use ($userId, $lastNotificationId) {
            // Use the last notification ID that was read before closing session
            $lastId = $lastNotificationId;
            $heartbeatInterval = 30; // Send heartbeat every 30 seconds
            $lastHeartbeat = time();
            $checkInterval = 5; // Check for new notifications every 5 seconds (reduced frequency to save resources)
            $lastCheck = time();
            $maxExecutionTime = 300; // 5 minutes max execution
            $startTime = time();
            
            // Reduce sleep time to be more responsive to disconnections
            // Smaller sleep = faster detection of client disconnect
            $sleepMicroseconds = 200000; // 0.2 seconds - faster response to disconnect
            
            // Send initial connection message
            echo ": connected\n\n";
            flush();
            
            // Keep connection alive
            while (true) {
                // CRITICAL: Check if client is still connected FIRST (before any other operations)
                // This ensures we exit immediately when client disconnects
                // Check multiple times to be absolutely sure
                if (connection_aborted() || connection_status() !== CONNECTION_NORMAL) {
                    // Client disconnected, exit immediately
                    break;
                }
                
                // Double check connection status
                $connectionStatus = @connection_status();
                if ($connectionStatus !== CONNECTION_NORMAL) {
                    break;
                }
                
                // Check if connection was aborted (more reliable check)
                if (@connection_aborted()) {
                    break;
                }
                
                // Also check if output was sent successfully (connection might be closed)
                // Try to flush and check if it succeeds
                @flush();
                if (@connection_aborted()) {
                    break;
                }
                
                // Check max execution time
                if (time() - $startTime > $maxExecutionTime) {
                    echo "event: timeout\n";
                    echo "data: " . json_encode(['message' => 'Connection timeout']) . "\n\n";
                    flush();
                    break;
                }
                
                $currentTime = time();
                
                // Check for new notifications
                if ($currentTime - $lastCheck >= $checkInterval) {
                    try {
                        // Get new notifications since last check
                        $newNotifications = Notification::forUser($userId)
                            ->where('id', '>', $lastId)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        // Send new notifications
                        foreach ($newNotifications as $notification) {
                            $data = [
                                'id' => $notification->id,
                                'type' => $notification->type,
                                'title' => $notification->title,
                                'message' => $notification->message,
                                'is_read' => $notification->is_read,
                                'related_id' => $notification->related_id,
                                'related_type' => $notification->related_type,
                                'created_at' => $notification->created_at->diffForHumans(),
                                'created_at_full' => $notification->created_at->format('d M Y H:i'),
                            ];
                            
                            // Send SSE formatted data
                            echo "event: notification\n";
                            echo "data: " . json_encode($data) . "\n\n";
                            
                            // Update last notification ID
                            $lastId = $notification->id;
                            
                            // Flush output to client immediately
                            flush();
                        }
                        
                        $lastCheck = $currentTime;
                    } catch (\Exception $e) {
                        Log::error('SSE stream error: ' . $e->getMessage());
                        echo "event: error\n";
                        echo "data: " . json_encode(['message' => 'Server error']) . "\n\n";
                        flush();
                        break;
                    }
                }
                
                // Send heartbeat to keep connection alive
                if ($currentTime - $lastHeartbeat >= $heartbeatInterval) {
                    echo ": heartbeat\n\n";
                    flush();
                    $lastHeartbeat = $currentTime;
                }
                
                       // Small sleep to prevent CPU overload
                       // CRITICAL: Check connection after sleep to exit quickly if disconnected
                       usleep($sleepMicroseconds); // 1 second
                       
                       // Check connection again after sleep
                       if (connection_aborted() || connection_status() !== CONNECTION_NORMAL) {
                           break;
                       }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no', // Disable nginx buffering
        ]);
    }
    
}
