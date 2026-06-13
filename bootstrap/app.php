<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // =====================================================
        // SECURITY MIDDLEWARE REGISTRATION
        // =====================================================

        // Security Headers — ditambahkan ke semua response (web + api)
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);

        // SQL Injection Detection — defence-in-depth pada web dan api
        $middleware->append(\App\Http\Middleware\SqlInjectionTestMiddleware::class);

        // Baris ini wajib untuk membaca Session dari frontend (Sanctum)
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Tambahkan throttle rate limiting ke API routes
        $middleware->api(append: [
            'throttle:api',
        ]);

        // Alias middleware untuk admin-only access
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// =====================================================
// RATE LIMITER CONFIGURATION
// =====================================================
// Dikonfigurasi di AppServiceProvider boot() sebagai alternatif.
// Rate limiter 'api' membatasi 60 request/menit per user/IP.