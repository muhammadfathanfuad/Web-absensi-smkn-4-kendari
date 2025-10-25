<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'start_date', 'end_date', 'week_type'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
