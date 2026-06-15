<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * =====================================================
 * MODEL: USER
 * =====================================================
 *
 * Implementasi Kriptografi yang digunakan pada model ini:
 *
 * 1. PASSWORD HASHING: Bcrypt dengan 12 rounds (BCRYPT_ROUNDS=12 di .env)
 *    - Password di-cast sebagai 'hashed' → otomatis di-hash saat disimpan
 *    - Bcrypt adalah one-way hash function yang aman untuk password
 *
 * 2. API TOKEN: Laravel Sanctum menggunakan SHA-256 untuk hash token
 *    - Token plaintext hanya diberikan sekali saat pembuatan
 *    - Yang disimpan di database adalah hash SHA-256
 *
 * 3. SESSION ENCRYPTION: AES-256-CBC (dikonfigurasi di config/app.php)
 *    - Session data dienkripsi sebelum disimpan (SESSION_ENCRYPT=true)
 *    - Menggunakan APP_KEY sebagai encryption key
 */
#[Fillable(['name', 'email', 'password', 'google_id', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * Password di-cast sebagai 'hashed' untuk keamanan:
     * - Otomatis di-hash menggunakan Bcrypt saat assignment
     * - Tidak perlu manual Hash::make() saat update password
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin == 1;
    }
}

