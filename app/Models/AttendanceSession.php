<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'teacher_id',
        'opened_by_user_id',
        'is_delegated',
        'delegation_reason',
        'session_number',
        'session_token',
        'qr_data',
        'session_type',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'qr_data' => 'array',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by_user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public function scopeForTimetable($query, $timetableId)
    {
        return $query->where('timetable_id', $timetableId);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    // Methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired();
    }

    public function deactivate()
    {
        try {
            $this->update(['is_active' => false]);
            Log::info('AttendanceSession deactivated successfully', ['id' => $this->id]);
        } catch (\Exception $e) {
            Log::error('Error deactivating AttendanceSession', [
                'id' => $this->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public static function generateSessionToken($timetableId, $teacherId, $sessionNumber)
    {
        return md5($timetableId . $teacherId . $sessionNumber . time() . rand(1000, 9999));
    }
}