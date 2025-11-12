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

if (!function_exists('user_photo_url')) {
    /**
     * Get user photo URL with fallback support
     * 
     * Handles cases where symlink might not exist on server
     * Falls back to direct storage access or default avatar
     * 
     * @param string|null $photo Photo filename
     * @param string $default Default avatar path
     * @return string Photo URL
     */
    function user_photo_url($photo = null, $default = '/images/users/avatar-1.jpg')
    {
        // Jika tidak ada foto, return default
        if (!$photo) {
            return asset($default);
        }
        
        // Path ke file di storage
        $storagePath = storage_path('app/public/users/' . $photo);
        
        // Cek apakah symlink public/storage ada
        $symlinkExists = file_exists(public_path('storage'));
        
        if ($symlinkExists) {
            // Symlink ada, gunakan path normal via asset()
            $publicPath = 'storage/users/' . $photo;
            
            // Cek apakah file benar-benar ada (melalui symlink)
            if (file_exists(public_path($publicPath))) {
                return asset($publicPath);
            }
        }
        
        // Symlink tidak ada atau file tidak ditemukan via symlink
        // Coba akses langsung dari storage (jika path bisa diakses)
        // Ini akan bekerja jika file ada di storage meskipun symlink tidak ada
        // dan server mengizinkan akses langsung ke storage
        
        // Cek apakah file ada di storage
        if (file_exists($storagePath)) {
            // Jika symlink tidak ada, gunakan route fallback
            // Route ini akan serve file dari storage
            return route('storage.users', ['filename' => $photo]);
        }
        
        // File tidak ditemukan, return default
        return asset($default);
    }
}

if (!function_exists('user_photo_exists')) {
    /**
     * Check if user photo file exists
     * 
     * @param string|null $photo Photo filename
     * @return bool
     */
    function user_photo_exists($photo = null)
    {
        if (!$photo) {
            return false;
        }
        
        // Cek via symlink
        $symlinkPath = public_path('storage/users/' . $photo);
        if (file_exists($symlinkPath)) {
            return true;
        }
        
        // Cek langsung di storage
        $storagePath = storage_path('app/public/users/' . $photo);
        return file_exists($storagePath);
    }
}

