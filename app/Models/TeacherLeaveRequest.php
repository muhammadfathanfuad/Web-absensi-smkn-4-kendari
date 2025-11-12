<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherLeaveRequest extends Model
{
    protected $fillable = [
        'teacher_id',
        'timetable_id',
        'leave_date',
        'end_date',
        'leave_type',
        'custom_leave_type',
        'reason',
        'supporting_document',
        'status',
        'substitute_user_id',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'leave_date' => 'date',
        'end_date' => 'date',
        'processed_at' => 'datetime',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class);
    }

    public function substitute(): BelongsTo
    {
        return $this->belongsTo(User::class, 'substitute_user_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    
    public function timetables(): HasMany
    {
        return $this->hasMany(TeacherLeaveRequestTimetable::class);
    }

    public function getLeaveTypeDisplayAttribute(): string
    {
        if ($this->leave_type === 'lainnya' && $this->custom_leave_type) {
            return $this->custom_leave_type;
        }

        return match($this->leave_type) {
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'keperluan-keluarga' => 'Keperluan Keluarga',
            'acara-keluarga' => 'Acara Keluarga',
            default => $this->leave_type
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}
