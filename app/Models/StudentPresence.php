<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPresence extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'status',
        'approval_count',
        'rejection_count',
        'check_in_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
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
