<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherPresence extends Model
{
    protected $fillable = [
        'teacher_id',
        'date',
        'status',
        'check_in_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
    ];

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'H' => 'Hadir',
            'A' => 'Alfa',
            'I' => 'Izin',
            'S' => 'Sakit',
            default => '-'
        };
    }
}
