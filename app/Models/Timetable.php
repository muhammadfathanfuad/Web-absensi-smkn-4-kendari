<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// --- PERUBAHAN DI SINI ---
use App\Models\Classroom; // Ganti dari 'Classes' menjadi 'Classroom'

class Timetable extends Model
{
    use HasFactory;

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    // --- DAN JUGA DI SINI ---
    public function classroom()
    {
        // Pastikan ini merujuk ke model 'Classroom' yang benar
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function sessions()
    {
        return $this->hasMany(ClassSession::class);
    }
}