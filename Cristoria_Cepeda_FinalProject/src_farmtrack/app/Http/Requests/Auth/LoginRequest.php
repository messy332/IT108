<?php

namespace App\Http\Requests\Auth;

use App\Models\LoginAttempt;
use App\Services\LoginSecurityService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected LoginSecurityService $securityService;

    public function __construct()
    {
        parent::__construct();
        $this->securityService = new LoginSecurityService();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Check for brute force lockout first
        $this->ensureNotLockedOut();
        
        // Then check rate limiting
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Record failed attempt
            $this->securityService->recordFailedAttempt($this->email, $this);
            
            RateLimiter::hit($this->throttleKey());

            $remainingAttempts = $this->securityService->getRemainingAttempts($this->email);
            
            $message = trans('auth.failed');
            if ($remainingAttempts > 0 && $remainingAttempts <= 3) {
                $message .= " You have {$remainingAttempts} attempt(s) remaining before your account is temporarily locked.";
            }

            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        // Record successful login
        $this->securityService->recordSuccessfulLogin($this->email, $this);
        
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login is not locked out due to brute force protection.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureNotLockedOut(): void
    {
        $lockoutCheck = $this->securityService->shouldBlockLogin($this->email, $this);

        if ($lockoutCheck['blocked']) {
            event(new Lockout($this));

            throw ValidationException::withMessages([
                'email' => $lockoutCheck['message'],
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
