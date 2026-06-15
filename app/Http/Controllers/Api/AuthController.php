<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register user baru via API.
     *
     * Validasi password menggunakan Password::defaults() yang dikonfigurasi
     * di AppServiceProvider (min 8, huruf besar+kecil, angka, simbol).
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Log registrasi berhasil
        SecurityAuditLog::logEvent('register_success', $user->id, $request, 'Registrasi user baru: ' . $user->email);

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Login user via API.
     *
     * Dilengkapi rate limiting: maksimal 5 percobaan per menit
     * untuk mencegah serangan brute force.
     */
    public function login(Request $request)
    {
        // Rate limiting: 5 percobaan per menit per email+IP
        $throttleKey = Str::transliterate(
            Str::lower($request->input('email')) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Log rate limit exceeded
            SecurityAuditLog::logEvent('login_rate_limited', null, $request,
                'API login rate limit tercapai untuk: ' . $request->input('email'));

            return response()->json([
                'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ], 429);
        }

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            // Log login gagal
            SecurityAuditLog::logEvent('login_failed', null, $request,
                'Login API gagal untuk: ' . $request->input('email'));

            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
            ]);
        }

        RateLimiter::clear($throttleKey);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Log login berhasil
        SecurityAuditLog::logEvent('login_success', $user->id, $request, 'Login API berhasil');

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            // Verifikasi token yang dikirim Flutter ke server Google
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->access_token);

            // Cek apakah user sudah terdaftar
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update google_id jika sebelumnya login manual
                $user->update(['google_id' => $googleUser->id]);
            } else {
                // Buat user baru jika belum ada
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null // Tidak perlu password
                ]);
            }

            // Buatkan token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            // Log Google login
            SecurityAuditLog::logEvent('google_login', $user->id, $request, 'Login via Google OAuth');

            // Kembalikan token ke Flutter
            return response()->json([
                'access_token' => $token,
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            // Log error
            SecurityAuditLog::logEvent('google_login_failed', null, $request,
                'Google login gagal: ' . $e->getMessage());

            return response()->json([
                'message' => 'Gagal verifikasi akun Google.',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        // Log logout
        SecurityAuditLog::logEvent('logout', $request->user()->id, $request, 'User logout via API');

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout'
        ]);
    }
}

