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
        'notes',
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
}