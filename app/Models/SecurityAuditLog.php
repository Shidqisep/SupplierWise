<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * =====================================================
 * MODEL: SECURITY AUDIT LOG
 * =====================================================
 *
 * Model untuk mencatat semua aktivitas keamanan dalam
 * aplikasi. Digunakan untuk:
 *
 * - Audit trail login/logout
 * - Deteksi percobaan serangan
 * - Analisa kerentanan
 * - Compliance & monitoring
 *
 * Event yang dicatat:
 * - login_success, login_failed, login_rate_limited
 * - register_success
 * - logout
 * - google_login, google_login_failed
 * - sql_injection_attempt
 * - suspicious_request
 * - password_changed
 */
class SecurityAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_type',
        'user_id',
        'ip_address',
        'user_agent',
        'request_url',
        'request_method',
        'details',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke User (opsional, bisa null untuk event anonim).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method untuk mencatat event keamanan.
     *
     * @param string       $eventType  Tipe event (login_success, sql_injection_attempt, dll.)
     * @param int|null     $userId     ID user (null jika anonim)
     * @param Request|null $request    HTTP request object
     * @param string       $details    Detail tambahan
     */
    public static function logEvent(
        string $eventType,
        ?int $userId = null,
        ?Request $request = null,
        string $details = ''
    ): self {
        return self::create([
            'event_type' => $eventType,
            'user_id' => $userId,
            'ip_address' => $request?->ip() ?? 'unknown',
            'user_agent' => $request?->userAgent() ?? 'unknown',
            'request_url' => $request?->fullUrl() ?? '',
            'request_method' => $request?->method() ?? '',
            'details' => $details,
            'created_at' => now(),
        ]);
    }
}
