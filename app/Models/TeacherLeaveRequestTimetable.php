<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherLeaveRequestTimetable extends Model
{
    protected $table = 'teacher_leave_request_timetables';
    
    protected $fillable = [
        'teacher_leave_request_id',
        'timetable_id',
        'leave_date',
    ];

    protected $casts = [
        'leave_date' => 'date',
    ];

    public function teacherLeaveRequest(): BelongsTo
    {
        return $this->belongsTo(TeacherLeaveRequest::class);
    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class);
    }
}
