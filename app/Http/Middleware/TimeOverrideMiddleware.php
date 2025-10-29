<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TimeOverrideService;

class TimeOverrideMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Override Carbon's now() function globally
        $this->overrideCarbonNow();
        
        // Override PHP's time() function globally
        $this->overridePhpTime();
        
        // Override PHP's date() function globally
        $this->overridePhpDate();
        
        return $next($request);
    }

    /**
     * Override Carbon's now() function globally
     */
    private function overrideCarbonNow()
    {
        // This will be handled by TimeOverrideService
        // Carbon::setTestNow() is not suitable for this use case
        // Instead, we'll rely on TimeOverrideService being used consistently
    }

    /**
     * Override PHP's time() function globally
     */
    private function overridePhpTime()
    {
        // We can't override PHP's built-in time() function
        // But we can provide helper functions
    }

    /**
     * Override PHP's date() function globally
     */
    private function overridePhpDate()
    {
        // We can't override PHP's built-in date() function
        // But we can provide helper functions
    }
}

