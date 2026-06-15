<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * =====================================================
 * MIDDLEWARE: ADMIN ONLY ACCESS
 * =====================================================
 *
 * Middleware untuk membatasi akses halaman tertentu
 * (seperti Security Dashboard) hanya untuk user
 * yang memiliki is_admin = true.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk administrator.');
        }

        return $next($request);
    }
}
