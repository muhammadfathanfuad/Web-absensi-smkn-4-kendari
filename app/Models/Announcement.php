<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Services\TimeOverrideService;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'target',
        'priority',
        'category',
        'is_active',
        'expires_at',
        'created_by',
        'read_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'read_by' => 'array',
    ];

    /**
     * Get the user who created the announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', TimeOverrideService::now());
                    });
    }

    /**
     * Scope to get announcements for specific target.
     */
    public function scopeForTarget($query, $target)
    {
        return $query->where(function ($q) use ($target) {
            $q->where('target', 'all')
              ->orWhere('target', $target);
        });
    }

    /**
     * Scope to get announcements by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get announcements by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Check if announcement is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get priority color class.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'normal' => 'info',
            default => 'info'
        };
    }

    /**
     * Get category color class.
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'penting' => 'danger',
            'akademik' => 'success',
            'kegiatan' => 'primary',
            'umum' => 'info',
            default => 'info'
        };
    }

    /**
     * Get target display name.
     */
    public function getTargetDisplayAttribute(): string
    {
        return match($this->target) {
            'all' => 'Semua',
            'teachers' => 'Guru',
            'students' => 'Siswa',
            default => 'Semua'
        };
    }

    /**
     * Check if announcement is read by specific user.
     */
    public function isReadBy($userId): bool
    {
        $readBy = $this->read_by ?? [];
        return in_array($userId, $readBy);
    }

    /**
     * Mark announcement as read by specific user.
     */
    public function markAsReadBy($userId): void
    {
        $readBy = $this->read_by ?? [];
        if (!in_array($userId, $readBy)) {
            $readBy[] = $userId;
            $this->read_by = $readBy;
            $this->save();
        }
    }

    /**
     * Mark announcement as unread by specific user.
     */
    public function markAsUnreadBy($userId): void
    {
        $readBy = $this->read_by ?? [];
        $readBy = array_filter($readBy, fn($id) => $id != $userId);
        $this->read_by = array_values($readBy);
        $this->save();
    }

    /**
     * Get read count.
     */
    public function getReadCountAttribute(): int
    {
        return count($this->read_by ?? []);
    }
}
