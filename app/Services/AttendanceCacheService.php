<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Subject;
use App\Models\Classroom;
use Carbon\Carbon;

class AttendanceCacheService
{
    /**
     * Cache TTL untuk data yang jarang berubah (subjects, classrooms)
     */
    const CACHE_TTL_STATIC = 3600; // 1 jam

    /**
     * Cache TTL untuk data yang lebih dinamis (statistics)
     */
    const CACHE_TTL_DYNAMIC = 300; // 5 menit

    /**
     * Get cached subjects list
     */
    public static function getSubjects()
    {
        return Cache::remember('subjects_list', self::CACHE_TTL_STATIC, function () {
            return Subject::orderBy('name')->get();
        });
    }

    /**
     * Get cached classrooms list
     */
    public static function getClassrooms()
    {
        return Cache::remember('classrooms_list', self::CACHE_TTL_STATIC, function () {
            return Classroom::orderBy('grade')->orderBy('name')->get();
        });
    }

    /**
     * Get cached subjects for a specific teacher
     */
    public static function getTeacherSubjects($teacherId)
    {
        $cacheKey = "teacher_subjects_{$teacherId}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL_STATIC, function () use ($teacherId) {
            return Subject::whereHas('classSubjects.teacher', function($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            })->distinct()->orderBy('name')->get();
        });
    }

    /**
     * Get cached classrooms for a specific teacher
     */
    public static function getTeacherClassrooms($teacherId)
    {
        $cacheKey = "teacher_classrooms_{$teacherId}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL_STATIC, function () use ($teacherId) {
            return Classroom::whereHas('classSubjects.teacher', function($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            })->distinct()->orderBy('grade')->orderBy('name')->get();
        });
    }

    /**
     * Invalidate cache untuk subjects
     */
    public static function invalidateSubjects()
    {
        Cache::forget('subjects_list');
        // Juga invalidate teacher-specific cache
        Cache::tags(['teacher_subjects'])->flush();
    }

    /**
     * Invalidate cache untuk classrooms
     */
    public static function invalidateClassrooms()
    {
        Cache::forget('classrooms_list');
        // Juga invalidate teacher-specific cache
        Cache::tags(['teacher_classrooms'])->flush();
    }

    /**
     * Invalidate cache untuk teacher-specific data
     */
    public static function invalidateTeacherCache($teacherId)
    {
        Cache::forget("teacher_subjects_{$teacherId}");
        Cache::forget("teacher_classrooms_{$teacherId}");
    }

    /**
     * Get cache key untuk attendance statistics
     */
    public static function getStatisticsCacheKey($teacherId, $dateFrom = null, $dateTo = null, $subjectId = null, $classroomId = null)
    {
        $params = [
            'teacher_id' => $teacherId,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'subject_id' => $subjectId,
            'classroom_id' => $classroomId,
        ];
        
        return 'attendance_stats_' . md5(serialize($params));
    }

    /**
     * Clear all attendance-related cache
     */
    public static function clearAll()
    {
        self::invalidateSubjects();
        self::invalidateClassrooms();
        // Clear statistics cache dengan pattern
        $keys = Cache::getRedis()->keys('attendance_stats_*');
        if ($keys) {
            Cache::getRedis()->del($keys);
        }
    }
}

