<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Izinkan pengisian massal untuk kolom-kolom ini
    protected $fillable = [
        'class_session_id',
        'student_id',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
        'session_id',
        'session_number',
        'is_on_time',
        'late_minutes',
    ];

    public function student()
    {
        // Relasi ke tabel students, merujuk ke kolom 'user_id'
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }
}