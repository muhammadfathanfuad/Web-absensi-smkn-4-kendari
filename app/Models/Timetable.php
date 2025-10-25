<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'class_subject_id', 'day_of_week', 'start_time', 'end_time', 'type', 'week_type', 'week_id', 'date', 'group_type', 'location_type', 'week_alternation'];

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

    // Accessor untuk mendapatkan subject melalui classSubject
    public function getSubjectAttribute()
    {
        return $this->classSubject?->subject;
    }

    // Accessor untuk mendapatkan teacher melalui classSubject
    public function getTeacherAttribute()
    {
        return $this->classSubject?->teacher;
    }

    // Accessor untuk mendapatkan classroom melalui classSubject
    public function getClassroomAttribute()
    {
        return $this->classSubject?->class;
    }

    public function sessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    /**
     * Get display name for group type
     */
    public function getGroupTypeDisplayAttribute()
    {
        return $this->group_type ? 'Kelompok ' . $this->group_type : '-';
    }

    /**
     * Get display name for location type
     */
    public function getLocationTypeDisplayAttribute()
    {
        return match($this->location_type) {
            'lab' => 'Lab',
            'theory' => 'Teori',
            default => '-'
        };
    }

    /**
     * Get display name for week alternation
     */
    public function getWeekAlternationDisplayAttribute()
    {
        return match($this->week_alternation) {
            'ganjil' => 'Ganjil',
            'genap' => 'Genap',
            default => '-'
        };
    }

    /**
     * Scope for specific group type
     */
    public function scopeGroupType($query, $groupType)
    {
        return $query->where('group_type', $groupType);
    }

    /**
     * Scope for specific week type
     */
    public function scopeWeekType($query, $weekType)
    {
        return $query->where('week_type', $weekType);
    }

    /**
     * Scope for specific location type
     */
    public function scopeLocationType($query, $locationType)
    {
        return $query->where('location_type', $locationType);
    }

    /**
     * Scope for specific week alternation
     */
    public function scopeWeekAlternation($query, $weekAlternation)
    {
        return $query->where('week_alternation', $weekAlternation);
    }
}
