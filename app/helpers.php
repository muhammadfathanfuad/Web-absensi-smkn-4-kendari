<?php

use App\Services\TimeOverrideService;

if (!function_exists('time_now')) {
    /**
     * Get current time (overridden or real)
     */
    function time_now()
    {
        return TimeOverrideService::now();
    }
}

if (!function_exists('time_today')) {
    /**
     * Get current date (overridden or real)
     */
    function time_today()
    {
        return TimeOverrideService::today();
    }
}

if (!function_exists('time_timestamp')) {
    /**
     * Get current timestamp (overridden or real)
     */
    function time_timestamp()
    {
        return TimeOverrideService::timestamp();
    }
}

if (!function_exists('time_format')) {
    /**
     * Get current time formatted (overridden or real)
     */
    function time_format($format = 'Y-m-d H:i:s')
    {
        return TimeOverrideService::format($format);
    }
}

if (!function_exists('time_day_of_week')) {
    /**
     * Get current day of week (overridden or real)
     */
    function time_day_of_week()
    {
        return TimeOverrideService::dayOfWeek();
    }
}

if (!function_exists('time_for_js')) {
    /**
     * Get current time for JavaScript
     */
    function time_for_js()
    {
        return TimeOverrideService::toISOString();
    }
}

if (!function_exists('time_locale')) {
    /**
     * Get current time with locale formatting
     */
    function time_locale($format)
    {
        return TimeOverrideService::localeFormat($format);
    }
}

if (!function_exists('time_translated')) {
    /**
     * Get current time with translated formatting
     */
    function time_translated($format)
    {
        return TimeOverrideService::translatedFormat($format);
    }
}

