<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\TimeOverrideService;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengumuman');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|in:all,teachers,students',
            'priority' => 'required|in:normal,high,urgent',
            'category' => 'required|in:umum,akademik,kegiatan,penting',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean'
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'target' => $request->target,
            'priority' => $request->priority,
            'category' => $request->category,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id()
        ]);

        // Send notifications to target users if announcement is active
        if ($announcement->is_active) {
            $this->sendAnnouncementNotifications($announcement);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dibuat',
            'data' => $announcement
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load('creator');
        
        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|in:all,teachers,students',
            'priority' => 'required|in:normal,high,urgent',
            'category' => 'required|in:umum,akademik,kegiatan,penting',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean'
        ]);

        $oldTarget = $announcement->target;
        $oldIsActive = $announcement->is_active;
        
        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'target' => $request->target,
            'priority' => $request->priority,
            'category' => $request->category,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active')
        ]);

        // Send notifications if announcement is activated or target changed
        if ($announcement->is_active && (!$oldIsActive || $oldTarget !== $announcement->target)) {
            $this->sendAnnouncementNotifications($announcement);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil diperbarui',
            'data' => $announcement
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dihapus'
        ]);
    }

    /**
     * Toggle announcement status.
     */
    public function toggleStatus(Request $request, Announcement $announcement)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        Log::info('Toggle status request:', [
            'announcement_id' => $announcement->id,
            'current_status' => $announcement->is_active,
            'new_status' => $request->is_active,
            'request_data' => $request->all()
        ]);

        $oldStatus = $announcement->is_active;
        
        $announcement->update([
            'is_active' => $request->is_active
        ]);

        // Refresh to get updated data
        $announcement->refresh();

        // Send notifications if announcement is activated
        if ($announcement->is_active && !$oldStatus) {
            $this->sendAnnouncementNotifications($announcement);
        }

        Log::info('After update:', [
            'announcement_id' => $announcement->id,
            'old_status' => $oldStatus,
            'new_status' => $announcement->is_active,
            'updated' => $announcement->wasChanged('is_active')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pengumuman berhasil diubah',
            'data' => [
                'id' => $announcement->id,
                'is_active' => $announcement->is_active,
                'was_changed' => $announcement->wasChanged('is_active')
            ]
        ]);
    }

    /**
     * Get announcements for specific target (for teacher/student pages).
     */
    public function getForTarget(Request $request, $target)
    {
        $announcements = Announcement::active()
            ->forTarget($target)
            ->with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }

    /**
     * Get announcements for teachers.
     */
    public function getForTeachers()
    {
        $userId = Auth::id();
        
        $announcements = Announcement::active()
            ->forTarget('teachers')
            ->with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) use ($userId) {
                $announcement->is_read_by_current_user = $announcement->isReadBy($userId);
                return $announcement;
            })
            ->sortBy(function ($announcement) {
                // Unread announcements first, then by priority and date
                return [$announcement->is_read_by_current_user ? 1 : 0, $announcement->priority === 'urgent' ? 0 : ($announcement->priority === 'high' ? 1 : 2), $announcement->created_at->timestamp];
            })
            ->values();


        return response()->json([
            'success' => true,
            'data' => $announcements,
            'last_updated' => TimeOverrideService::now()->toISOString()
        ]);
    }

    /**
     * Get announcements for students.
     */
    public function getForStudents()
    {
        $userId = Auth::id();
        
        $announcements = Announcement::active()
            ->forTarget('students')
            ->with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) use ($userId) {
                $announcement->is_read_by_current_user = $announcement->isReadBy($userId);
                return $announcement;
            })
            ->sortBy(function ($announcement) {
                // Unread announcements first, then by priority and date
                return [$announcement->is_read_by_current_user ? 1 : 0, $announcement->priority === 'urgent' ? 0 : ($announcement->priority === 'high' ? 1 : 2), $announcement->created_at->timestamp];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $announcements,
            'last_updated' => TimeOverrideService::now()->toISOString()
        ]);
    }

    /**
     * Mark announcement as read by current user.
     */
    public function markAsRead(Request $request, Announcement $announcement)
    {
        $userId = Auth::id();
        
        try {
            $announcement->markAsReadBy($userId);
            
            // Refresh the model to get updated data
            $announcement->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman ditandai sebagai telah dibaca',
                'data' => [
                    'id' => $announcement->id,
                    'is_read' => true,
                    'read_count' => $announcement->read_count,
                    'read_by' => $announcement->read_by
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai pengumuman sebagai telah dibaca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark announcement as unread by current user.
     */
    public function markAsUnread(Request $request, Announcement $announcement)
    {
        $userId = Auth::id();
        
        $announcement->markAsUnreadBy($userId);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman ditandai sebagai belum dibaca',
            'data' => [
                'id' => $announcement->id,
                'is_read' => false,
                'read_count' => $announcement->read_count
            ]
        ]);
    }

    /**
     * Send notifications to target users for an announcement.
     */
    private function sendAnnouncementNotifications(Announcement $announcement)
    {
        try {
            // Get target users based on announcement target
            $users = $this->getTargetUsers($announcement->target);

            // Prepare notification data
            $priorityLabels = [
                'urgent' => 'Penting',
                'high' => 'Tinggi',
                'normal' => 'Normal'
            ];
            
            $priorityLabel = $priorityLabels[$announcement->priority] ?? 'Normal';
            
            // Truncate content for notification message
            $message = strlen($announcement->content) > 150 
                ? substr($announcement->content, 0, 150) . '...' 
                : $announcement->content;

            // Check if notifications already exist for this announcement
            $existingNotificationUserIds = Notification::where('related_id', $announcement->id)
                ->where('related_type', Announcement::class)
                ->where('type', 'announcement')
                ->pluck('user_id')
                ->toArray();

            // Create notifications for each user (skip if notification already exists)
            $notifications = [];
            foreach ($users as $user) {
                // Skip if notification already exists for this user
                if (in_array($user->id, $existingNotificationUserIds)) {
                    continue;
                }

                $notifications[] = [
                    'user_id' => $user->id,
                    'type' => 'announcement',
                    'title' => $announcement->title,
                    'message' => $message,
                    'related_id' => $announcement->id,
                    'related_type' => Announcement::class,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert notifications for better performance
            if (!empty($notifications)) {
                Notification::insert($notifications);
                
                Log::info('Announcement notifications sent', [
                    'announcement_id' => $announcement->id,
                    'target' => $announcement->target,
                    'notifications_count' => count($notifications)
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send announcement notifications', [
                'announcement_id' => $announcement->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get target users based on announcement target.
     */
    private function getTargetUsers(string $target)
    {
        $query = User::where('status', 'active');

        switch ($target) {
            case 'teachers':
                $query->whereHas('roles', function($q) {
                    $q->where('name', 'teacher');
                });
                break;
            
            case 'students':
                $query->whereHas('roles', function($q) {
                    $q->where('name', 'student');
                });
                break;
            
            case 'all':
            default:
                // Get all active users with teacher or student role
                $query->whereHas('roles', function($q) {
                    $q->whereIn('name', ['teacher', 'student']);
                });
                break;
        }

        return $query->get();
    }
}