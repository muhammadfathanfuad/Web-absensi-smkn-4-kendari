<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = Notification::forUser(Auth::id())->unread()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications
     */
    public function getRecent(Request $request)
    {
        $limit = $request->input('limit', 10);
        $unreadOnly = $request->boolean('unread_only', false);

        $query = Notification::forUser(Auth::id())->recent($limit);
        
        if ($unreadOnly) {
            $query->unread();
        }

        $notifications = $query->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at->diffForHumans(),
                'created_at_full' => $notification->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::forUser(Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::forUser(Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }
}
