<?php

namespace App\Services;

use App\Models\LoginAttempt;
use Illuminate\Http\Request;

class LoginSecurityService
{
    // Maximum failed attempts before account lockout
    const MAX_EMAIL_ATTEMPTS = 5;
    
    // Maximum failed attempts from single IP before IP lockout
    const MAX_IP_ATTEMPTS = 15;
    
    // Lockout duration in minutes for email-based lockout
    const EMAIL_LOCKOUT_MINUTES = 15;
    
    // Lockout duration in minutes for IP-based lockout
    const IP_LOCKOUT_MINUTES = 30;

    /**
     * Check if login should be blocked
     */
    public function shouldBlockLogin(string $email, Request $request): array
    {
        $ipAddress = $request->ip();

        // Check IP lockout first (more severe)
        if (LoginAttempt::isIpLockedOut($ipAddress, self::MAX_IP_ATTEMPTS, self::IP_LOCKOUT_MINUTES)) {
            $remaining = $this->getRemainingIpLockoutTime($ipAddress);
            return [
                'blocked' => true,
                'reason' => 'ip_locked',
                'message' => "Too many login attempts from your IP address. Please try again in " . ceil($remaining / 60) . " minutes.",
                'remaining_seconds' => $remaining,
            ];
        }

        // Check email lockout
        if (LoginAttempt::isEmailLockedOut($email, self::MAX_EMAIL_ATTEMPTS, self::EMAIL_LOCKOUT_MINUTES)) {
            $remaining = LoginAttempt::getRemainingLockoutTime($email, self::EMAIL_LOCKOUT_MINUTES);
            return [
                'blocked' => true,
                'reason' => 'email_locked',
                'message' => "This account has been temporarily locked due to too many failed login attempts. Please try again in " . ceil($remaining / 60) . " minutes.",
                'remaining_seconds' => $remaining,
            ];
        }

        return ['blocked' => false];
    }

    /**
     * Record a failed login attempt
     */
    public function recordFailedAttempt(string $email, Request $request): void
    {
        LoginAttempt::recordAttempt(
            $email,
            $request->ip(),
            $request->userAgent(),
            false
        );
    }

    /**
     * Record a successful login attempt
     */
    public function recordSuccessfulLogin(string $email, Request $request): void
    {
        LoginAttempt::recordAttempt(
            $email,
            $request->ip(),
            $request->userAgent(),
            true
        );

        // Clear previous failed attempts for this email
        LoginAttempt::clearAttemptsForEmail($email);
    }

    /**
     * Get remaining attempts before lockout
     */
    public function getRemainingAttempts(string $email): int
    {
        $failedAttempts = LoginAttempt::getFailedAttemptsForEmail($email, self::EMAIL_LOCKOUT_MINUTES);
        return max(0, self::MAX_EMAIL_ATTEMPTS - $failedAttempts);
    }

    /**
     * Get remaining IP lockout time
     */
    private function getRemainingIpLockoutTime(string $ipAddress): int
    {
        $lastAttempt = LoginAttempt::where('ip_address', $ipAddress)
            ->where('successful', false)
            ->orderBy('attempted_at', 'desc')
            ->first();

        if (!$lastAttempt) {
            return 0;
        }

        $lockoutEnds = $lastAttempt->attempted_at->addMinutes(self::IP_LOCKOUT_MINUTES);
        $remaining = now()->diffInSeconds($lockoutEnds, false);

        return max(0, $remaining);
    }
}
