<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'prevent-back-logout' => \App\Http\Middleware\PreventBackAfterLogout::class,
            'security-headers' => \App\Http\Middleware\SecurityHeaders::class,
            'session-integrity' => \App\Http\Middleware\ValidateSessionIntegrity::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.change' => \App\Http\Middleware\CheckPasswordChange::class,
        ]);
        
        // Add security headers and session integrity check to all web requests
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\ValidateSessionIntegrity::class,
        ]);
        
        // Use custom Authenticate middleware to redirect to welcome page
        $middleware->redirectGuestsTo(fn () => route('welcome'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
