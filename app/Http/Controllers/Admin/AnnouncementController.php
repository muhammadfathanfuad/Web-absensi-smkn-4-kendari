<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
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

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'target' => $request->target,
            'priority' => $request->priority,
            'category' => $request->category,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active')
        ]);

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
}