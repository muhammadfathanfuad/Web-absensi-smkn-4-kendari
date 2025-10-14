<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XiTimetable extends Model
{
    use HasFactory;

    protected $table = 'timetables';
    
    protected $fillable = [
        'term_id',
        'class_subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'type',
        'week_type',
        'group_type',
        'location_type',
        'week_alternation'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    /**
     * Get the term that owns the timetable.
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get the class subject that owns the timetable.
     */
    public function classSubject(): BelongsTo
    {
        return $this->belongsTo(ClassSubject::class);
    }

    /**
     * Scope for XI classes only
     */
    public function scopeXiClasses($query)
    {
        return $query->whereHas('classSubject.class', function($q) {
            $q->where('grade', '11');
        });
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
}
