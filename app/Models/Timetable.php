<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'class_subject_id', 'day_of_week', 'start_time', 'end_time', 'type', 'week_type', 'week_id', 'date'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function sessions()
    {
        return $this->hasMany(ClassSession::class);
    }
}
