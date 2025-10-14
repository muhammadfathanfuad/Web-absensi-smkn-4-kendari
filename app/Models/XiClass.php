<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class XiClass extends Model
{
    use HasFactory;

    protected $table = 'classes';
    
    protected $fillable = [
        'name',
        'grade',
        'group_type',
        'location_preference'
    ];

    /**
     * Get the class subjects for the class.
     */
    public function classSubjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    /**
     * Scope for XI classes only
     */
    public function scopeXiClasses($query)
    {
        return $query->where('grade', '11');
    }

    /**
     * Scope for specific group type
     */
    public function scopeGroupType($query, $groupType)
    {
        return $query->where('group_type', $groupType);
    }

    /**
     * Scope for specific location preference
     */
    public function scopeLocationPreference($query, $locationPreference)
    {
        return $query->where('location_preference', $locationPreference);
    }

    /**
     * Get display name for group type
     */
    public function getGroupTypeDisplayAttribute()
    {
        return $this->group_type ? 'Kelompok ' . $this->group_type : '-';
    }

    /**
     * Get display name for location preference
     */
    public function getLocationPreferenceDisplayAttribute()
    {
        return match($this->location_preference) {
            'lab' => 'Lab',
            'theory' => 'Teori',
            default => '-'
        };
    }

    /**
     * Get formatted class name with group
     */
    public function getFormattedNameAttribute()
    {
        $groupSuffix = $this->group_type ? '-' . $this->group_type : '';
        return $this->name . $groupSuffix;
    }
}
