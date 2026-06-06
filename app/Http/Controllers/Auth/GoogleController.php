<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah terdaftar berdasarkan email atau google_id
            $findUser = User::where('email', $googleUser->email)->first();

            if ($findUser) {
                // Jika user sudah ada, update google_id (kalau misal sebelumnya dia register manual)
                $findUser->update(['google_id' => $googleUser->id]);
                Auth::login($findUser);
            } else {
                // Jika user baru, buat data user baru
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null // Tidak perlu password
                ]);

                Auth::login($newUser);
            }

            return redirect()->intended('/dashboard'); // Arahkan ke halaman Livewire admin/dashboard-mu

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login menggunakan Google.');
        }
    }
}
