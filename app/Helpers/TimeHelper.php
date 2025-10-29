<?php

namespace App\Helpers;

use App\Services\TimeOverrideService;

class TimeHelper
{
    /**
     * Get current time (overridden or real)
     */
    public static function now()
    {
        return TimeOverrideService::now();
    }

    /**
     * Get current date (overridden or real)
     */
    public static function today()
    {
        return TimeOverrideService::today();
    }

    /**
     * Get current timestamp (overridden or real)
     */
    public static function timestamp()
    {
        return TimeOverrideService::timestamp();
    }

    /**
     * Get current time formatted (overridden or real)
     */
    public static function format($format = 'Y-m-d H:i:s')
    {
        return TimeOverrideService::format($format);
    }

    /**
     * Get current day of week (overridden or real)
     */
    public static function dayOfWeek()
    {
        return TimeOverrideService::dayOfWeek();
    }

    /**
     * Get current time for JavaScript
     */
    public static function forJS()
    {
        return TimeOverrideService::toISOString();
    }

    /**
     * Get current time with locale formatting
     */
    public static function locale($format)
    {
        return TimeOverrideService::localeFormat($format);
    }

    /**
     * Get current time with translated formatting
     */
    public static function translated($format)
    {
        return TimeOverrideService::translatedFormat($format);
    }
}

