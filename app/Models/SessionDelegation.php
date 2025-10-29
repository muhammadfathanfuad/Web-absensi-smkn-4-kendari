<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionDelegation extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'original_teacher_id',
        'delegated_to_user_id',
        'type',
        'valid_from',
        'valid_until',
        'status',
        'admin_notes',
        'created_by',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    // Relationships
    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function originalTeacher()
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id', 'user_id');
    }

    public function delegatedTo()
    {
        return $this->belongsTo(User::class, 'delegated_to_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('delegated_to_user_id', $userId);
    }

    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->where('type', 'permanent')
              ->orWhere(function($query) {
                  $query->where('type', 'temporary')
                        ->where('valid_until', '>=', now()->toDateString());
              });
        });
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isExpired()
    {
        if ($this->type === 'permanent') {
            return false;
        }

        return $this->valid_until && $this->valid_until->isPast();
    }

    public function revoke()
    {
        $this->update(['status' => 'revoked']);
    }
}
