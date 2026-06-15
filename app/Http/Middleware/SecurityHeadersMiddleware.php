<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * =====================================================
 * MIDDLEWARE: SECURITY HEADERS
 * =====================================================
 *
 * Middleware ini menambahkan HTTP Security Headers pada
 * setiap response untuk melindungi aplikasi dari:
 *
 * - Clickjacking          → X-Frame-Options
 * - MIME sniffing         → X-Content-Type-Options
 * - XSS (reflected)      → X-XSS-Protection
 * - Information leakage   → Referrer-Policy
 * - Mixed content         → Content-Security-Policy
 * - Downgrade attacks     → Strict-Transport-Security
 * - DNS prefetch abuse    → X-DNS-Prefetch-Control
 * - Permission abuse      → Permissions-Policy
 *
 * Referensi: OWASP Secure Headers Project
 */
class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mencegah halaman di-embed dalam iframe (anti-Clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Mencegah browser menebak MIME type (anti-MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mengaktifkan built-in XSS filter browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Mengontrol informasi referrer yang dikirim ke situs lain
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy — membatasi sumber resource yang boleh dimuat
        // Menggunakan Report-Only di development agar tidak memblokir resource
        // Di production, ganti 'Content-Security-Policy-Report-Only' menjadi 'Content-Security-Policy'
        $cspHeader = app()->environment('production')
            ? 'Content-Security-Policy'
            : 'Content-Security-Policy-Report-Only';

        $response->headers->set($cspHeader,
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://accounts.google.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net; " .
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self' https://accounts.google.com wss: ws:; " .
            "frame-src https://accounts.google.com; " .
            "object-src 'none'; " .
            "base-uri 'self';"
        );

        // HTTP Strict Transport Security — paksa HTTPS (saat production)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Kontrol DNS prefetching
        $response->headers->set('X-DNS-Prefetch-Control', 'off');

        // Permissions Policy — batasi akses fitur browser
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        return $response;
    }
}
