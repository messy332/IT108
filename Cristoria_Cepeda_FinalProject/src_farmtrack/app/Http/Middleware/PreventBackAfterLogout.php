<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackAfterLogout
{
    /**
     * Handle an incoming request.
     * Prevents browser back button from showing cached authenticated pages after logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set headers to prevent caching of authenticated pages
        return $response
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
