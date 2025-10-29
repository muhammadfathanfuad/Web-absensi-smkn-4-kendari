<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

class TimeOverrideServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade directives for time override
        Blade::directive('time_now', function () {
            return "<?php echo \App\Services\TimeOverrideService::now(); ?>";
        });

        Blade::directive('time_today', function () {
            return "<?php echo \App\Services\TimeOverrideService::today(); ?>";
        });

        Blade::directive('time_format', function ($format) {
            return "<?php echo \App\Services\TimeOverrideService::format($format); ?>";
        });

        Blade::directive('time_locale', function ($format) {
            return "<?php echo \App\Services\TimeOverrideService::localeFormat($format); ?>";
        });

        Blade::directive('time_translated', function ($format) {
            return "<?php echo \App\Services\TimeOverrideService::translatedFormat($format); ?>";
        });

        // Override Carbon's now() method globally
        $this->overrideCarbonNow();
    }

    /**
     * Override Carbon's now() method globally
     */
    private function overrideCarbonNow()
    {
        // We can't override Carbon's now() method directly
        // But we can create a custom Carbon instance
        Carbon::macro('overrideNow', function () {
            return \App\Services\TimeOverrideService::now();
        });
    }
}

