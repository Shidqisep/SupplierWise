<?php

namespace App\Http\Middleware;

use App\Models\SecurityAuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * =====================================================
 * MIDDLEWARE: DETEKSI SQL INJECTION
 * =====================================================
 *
 * Middleware pertahanan berlapis (defence-in-depth) untuk
 * mendeteksi pola-pola SQL Injection pada input request.
 *
 * Meskipun Laravel Eloquent ORM sudah menggunakan
 * parameterized queries, middleware ini menambahkan
 * lapisan proteksi tambahan dengan:
 *
 * 1. Mendeteksi pola SQL Injection umum pada semua input
 * 2. Mencatat percobaan serangan ke audit log
 * 3. Memblokir request yang mencurigakan
 *
 * Pola yang dideteksi:
 * - UNION SELECT, DROP TABLE, INSERT INTO, dll.
 * - Komentar SQL (-- atau /*)
 * - OR/AND boolean bypass (1=1, 'a'='a')
 * - Hex encoding (0x...)
 * - SLEEP/BENCHMARK (time-based SQLi)
 * - LOAD_FILE, INTO OUTFILE (file-based SQLi)
 */
class SqlInjectionTestMiddleware
{
    /**
     * Pola-pola SQL Injection yang umum.
     */
    protected array $patterns = [
        // DDL/DML injection
        '/(\bunion\b\s+\bselect\b)/i',
        '/(\bdrop\b\s+\btable\b)/i',
        '/(\binsert\b\s+\binto\b)/i',
        '/(\bdelete\b\s+\bfrom\b)/i',
        '/(\bupdate\b\s+\bset\b)/i',
        '/(\balter\b\s+\btable\b)/i',
        '/(\bcreate\b\s+\btable\b)/i',
        '/(\btruncate\b\s+\btable\b)/i',

        // Boolean-based blind SQLi
        '/(\bor\b\s+1\s*=\s*1)/i',
        '/(\band\b\s+1\s*=\s*1)/i',
        "/(\'|\")(\s*)(or|and)(\s*)(\'|\")(\s*)(=)/i",

        // Comment-based
        '/(--\s)/i',
        '/(\/\*.*\*\/)/i',

        // Time-based blind SQLi
        '/(\bsleep\b\s*\()/i',
        '/(\bbenchmark\b\s*\()/i',
        '/(\bwaitfor\b\s+\bdelay\b)/i',

        // File-based
        '/(\bload_file\b\s*\()/i',
        '/(\binto\b\s+\boutfile\b)/i',
        '/(\binto\b\s+\bdumpfile\b)/i',

        // Hex encoding
        '/(0x[0-9a-f]{8,})/i',

        // Information gathering
        '/(\binformation_schema\b)/i',
        '/(\bsys\.)/i',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Gabungkan semua input (query params, body, route params)
        $inputs = array_merge(
            $request->all(),
            $request->route() ? $request->route()->parameters() : []
        );

        foreach ($inputs as $key => $value) {
            if (is_string($value) && $this->containsSqlInjection($value)) {
                // Log percobaan SQL Injection
                SecurityAuditLog::logEvent(
                    'sql_injection_attempt',
                    $request->user()?->id,
                    $request,
                    "Pola SQL Injection terdeteksi pada parameter '{$key}': " . \Illuminate\Support\Str::limit($value, 200)
                );

                Log::warning('SQL Injection attempt detected', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'parameter' => $key,
                    'value' => mb_substr($value, 0, 200),
                    'user_id' => $request->user()?->id,
                ]);

                return response()->json([
                    'message' => 'Permintaan ditolak: input mengandung karakter tidak valid.',
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Cek apakah string mengandung pola SQL Injection.
     */
    protected function containsSqlInjection(string $value): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }
}
