<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;

class Timetable extends Model
{
    use HasFactory;

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}


