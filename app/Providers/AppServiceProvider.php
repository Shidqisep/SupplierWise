<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // =====================================================
        // KEBIJAKAN PASSWORD KUAT (Strong Password Policy)
        // =====================================================
        // Menetapkan aturan default password di seluruh aplikasi:
        // - Minimal 8 karakter
        // - Harus ada huruf besar dan huruf kecil (mixedCase)
        // - Harus ada angka (numbers)
        // - Harus ada simbol/karakter khusus (symbols)
        // - Tidak boleh password yang sudah pernah bocor (uncompromised)
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        // =====================================================
        // API RATE LIMITING (Anti-DDoS)
        // =====================================================
        // Membatasi jumlah request API per menit:
        // - User yang login: 60 request/menit berdasarkan user ID
        // - User anonim: 60 request/menit berdasarkan IP address
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        if(app()->environment('production')){
            URL::forceScheme('https');
        }
    }
}

