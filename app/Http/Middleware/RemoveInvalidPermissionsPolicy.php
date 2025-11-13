<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveInvalidPermissionsPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add Permissions-Policy header to allow camera access
        // This is required for getUserMedia() to work on hosted servers
        $response->headers->set(
            'Permissions-Policy',
            'camera=(self), microphone=(), geolocation=(), interest-cohort=()'
        );
        
        // Also set Feature-Policy for older browsers (deprecated but some still use it)
        $response->headers->set(
            'Feature-Policy',
            'camera *; microphone \'none\'; geolocation \'none\''
        );
        
        return $response;
    }
}
