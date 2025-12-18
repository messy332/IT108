<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     * Prevents browser back button from showing cached pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strong cache prevention headers
        return $response
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
    }
}
