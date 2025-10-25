<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
        'student_id',
        'leave_type',
        'custom_leave_type',
        'start_date',
        'end_date',
        'reason',
        'supporting_document',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
        'approved_by_teachers',
        'rejected_by_teachers',
        'overall_status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'processed_at' => 'datetime',
        'approved_by_teachers' => 'array',
        'rejected_by_teachers' => 'array'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
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

    /**
     * Check if a teacher has already approved this request
     */
    public function isApprovedByTeacher($teacherId): bool
    {
        return in_array($teacherId, $this->approved_by_teachers ?? []);
    }

    /**
     * Check if a teacher has already rejected this request
     */
    public function isRejectedByTeacher($teacherId): bool
    {
        return in_array($teacherId, $this->rejected_by_teachers ?? []);
    }

    /**
     * Check if a teacher can still take action on this request
     */
    public function canTeacherTakeAction($teacherId): bool
    {
        // Each teacher can always take action (approve or reject) regardless of other teachers' actions
        // Only prevent if they already took action
        return !$this->isApprovedByTeacher($teacherId) && !$this->isRejectedByTeacher($teacherId);
    }

    /**
     * Add teacher approval
     */
    public function addTeacherApproval($teacherId): void
    {
        $approved = $this->approved_by_teachers ?? [];
        if (!in_array($teacherId, $approved)) {
            $approved[] = $teacherId;
            $this->approved_by_teachers = $approved;
            
            // Don't automatically change overall status - let each teacher decide independently
            // Only update if this is the first approval and no rejections yet
            if (count($approved) === 1 && empty($this->rejected_by_teachers)) {
                $this->overall_status = 'approved';
                $this->status = 'approved';
            } else {
                $this->overall_status = 'partially_approved';
            }
        }
    }

    /**
     * Add teacher rejection
     */
    public function addTeacherRejection($teacherId): void
    {
        $rejected = $this->rejected_by_teachers ?? [];
        if (!in_array($teacherId, $rejected)) {
            $rejected[] = $teacherId;
            $this->rejected_by_teachers = $rejected;
            
            // Don't automatically change overall status - let each teacher decide independently
            // Only update if this is the first rejection and no approvals yet
            if (count($rejected) === 1 && empty($this->approved_by_teachers)) {
                $this->overall_status = 'rejected';
                $this->status = 'rejected';
            } else {
                $this->overall_status = 'partially_approved';
            }
        }
    }
}
