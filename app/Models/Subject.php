<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
