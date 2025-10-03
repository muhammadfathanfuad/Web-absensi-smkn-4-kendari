<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
        'timetable_id',
        'date',
        'start_time_actual',
        'end_time_actual',
        'status',
        'qr_token',
        'qr_expires_at',
        'opened_by',
        'closed_by',
        'last_activity',
        'ip_address',
        'user_agent',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
