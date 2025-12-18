<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'successful',
        'attempted_at',
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Record a login attempt
     */
    public static function recordAttempt(string $email, string $ipAddress, ?string $userAgent, bool $successful): self
    {
        return self::create([
            'email' => strtolower($email),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'successful' => $successful,
            'attempted_at' => now(),
        ]);
    }

    /**
     * Get failed attempts count for email in the last X minutes
     */
    public static function getFailedAttemptsForEmail(string $email, int $minutes = 15): int
    {
        return self::where('email', strtolower($email))
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Get failed attempts count for IP in the last X minutes
     */
    public static function getFailedAttemptsForIp(string $ipAddress, int $minutes = 15): int
    {
        return self::where('ip_address', $ipAddress)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Check if email is locked out
     */
    public static function isEmailLockedOut(string $email, int $maxAttempts = 5, int $lockoutMinutes = 15): bool
    {
        return self::getFailedAttemptsForEmail($email, $lockoutMinutes) >= $maxAttempts;
    }

    /**
     * Check if IP is locked out
     */
    public static function isIpLockedOut(string $ipAddress, int $maxAttempts = 10, int $lockoutMinutes = 30): bool
    {
        return self::getFailedAttemptsForIp($ipAddress, $lockoutMinutes) >= $maxAttempts;
    }

    /**
     * Get remaining lockout time for email in seconds
     */
    public static function getRemainingLockoutTime(string $email, int $lockoutMinutes = 15): int
    {
        $lastAttempt = self::where('email', strtolower($email))
            ->where('successful', false)
            ->orderBy('attempted_at', 'desc')
            ->first();

        if (!$lastAttempt) {
            return 0;
        }

        $lockoutEnds = $lastAttempt->attempted_at->addMinutes($lockoutMinutes);
        $remaining = now()->diffInSeconds($lockoutEnds, false);

        return max(0, $remaining);
    }

    /**
     * Clear old login attempts (for cleanup)
     */
    public static function clearOldAttempts(int $daysOld = 7): int
    {
        return self::where('attempted_at', '<', now()->subDays($daysOld))->delete();
    }

    /**
     * Clear attempts for email after successful login
     */
    public static function clearAttemptsForEmail(string $email): int
    {
        return self::where('email', strtolower($email))
            ->where('successful', false)
            ->delete();
    }
}
