<?php

namespace App\Services;

use Carbon\Carbon;

class TimeOverrideService
{
    /**
     * Get current time (real or overridden)
     */
    public static function now()
    {
        if (session('time_override_active')) {
            $overrideTime = session('time_override_datetime');
            if ($overrideTime) {
                return Carbon::parse($overrideTime);
            }
        }
        
        return Carbon::now();
    }

    /**
     * Get current date (real or overridden)
     */
    public static function today()
    {
        return self::now()->toDateString();
    }

    /**
     * Get current day of week (real or overridden)
     */
    public static function dayOfWeek()
    {
        return self::now()->dayOfWeekIso;
    }

    /**
     * Set time override
     */
    public static function setOverride($date, $time)
    {
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time);
        
        session([
            'time_override_active' => true,
            'time_override_date' => $date,
            'time_override_time' => $time,
            'time_override_datetime' => $datetime->toDateTimeString()
        ]);
    }

    /**
     * Clear time override
     */
    public static function clearOverride()
    {
        session()->forget([
            'time_override_active',
            'time_override_date', 
            'time_override_time',
            'time_override_datetime'
        ]);
    }

    /**
     * Check if time override is active
     */
    public static function isActive()
    {
        return session('time_override_active', false);
    }

    /**
     * Get override info
     */
    public static function getInfo()
    {
        return [
            'is_active' => self::isActive(),
            'current_time' => self::now()->toDateTimeString(),
            'real_time' => Carbon::now()->toDateTimeString(),
            'override_date' => session('time_override_date'),
            'override_time' => session('time_override_time'),
            'override_datetime' => session('time_override_datetime')
        ];
    }

    /**
     * Get preset scenarios
     */
    public static function getPresetScenarios()
    {
        return [
            [
                'name' => 'Pagi - Sebelum Jam Pertama',
                'date' => '2025-01-15',
                'time' => '06:30:00',
                'description' => 'Testing sebelum jam pelajaran dimulai'
            ],
            [
                'name' => 'Pagi - Jam Pertama Berlangsung',
                'date' => '2025-01-15',
                'time' => '07:30:00',
                'description' => 'Testing saat jam pelajaran pertama berlangsung'
            ],
            [
                'name' => 'Siang - Jam Istirahat',
                'date' => '2025-01-15',
                'time' => '10:00:00',
                'description' => 'Testing saat jam istirahat'
            ],
            [
                'name' => 'Siang - Jam Pelajaran Berlangsung',
                'date' => '2025-01-15',
                'time' => '11:00:00',
                'description' => 'Testing saat jam pelajaran siang'
            ],
            [
                'name' => 'Sore - Setelah Jam Terakhir',
                'date' => '2025-01-15',
                'time' => '15:30:00',
                'description' => 'Testing setelah jam pelajaran selesai'
            ],
            [
                'name' => 'Malam - Testing Mode',
                'date' => '2025-01-15',
                'time' => '20:00:00',
                'description' => 'Testing di malam hari'
            ]
        ];
    }
}
