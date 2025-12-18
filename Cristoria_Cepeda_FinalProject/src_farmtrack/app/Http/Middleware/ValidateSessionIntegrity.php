<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateSessionIntegrity
{
    /**
     * Handle an incoming request.
     * Validates session integrity to prevent session hijacking.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $sessionIp = $request->session()->get('user_ip');
            $currentIp = $request->ip();

            // If IP changed significantly (different subnet), invalidate session
            // This helps prevent session hijacking while allowing for minor IP changes
            if ($sessionIp && !$this->isSameSubnet($sessionIp, $currentIp)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('status', 'Your session has expired for security reasons. Please log in again.');
            }

            // Update session IP if not set
            if (!$sessionIp) {
                $request->session()->put('user_ip', $currentIp);
            }
        }

        return $next($request);
    }

    /**
     * Check if two IPs are in the same /24 subnet (for IPv4)
     */
    private function isSameSubnet(string $ip1, string $ip2): bool
    {
        // For localhost/development, always return true
        if ($ip1 === '127.0.0.1' || $ip2 === '127.0.0.1') {
            return true;
        }

        // Handle IPv4
        if (filter_var($ip1, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && 
            filter_var($ip2, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts1 = explode('.', $ip1);
            $parts2 = explode('.', $ip2);
            
            // Compare first 3 octets (same /24 subnet)
            return $parts1[0] === $parts2[0] && 
                   $parts1[1] === $parts2[1] && 
                   $parts1[2] === $parts2[2];
        }

        // For IPv6 or mixed, be more lenient
        return true;
    }
}
