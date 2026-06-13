<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\AdminDashboard;

// Halaman utama (opsional, biasanya diarahkan ke login atau halaman landing)
Route::redirect('/', '/login');

// ==========================================
// RUTE GOOGLE OAUTH (Bebas diakses tanpa login)
// ==========================================
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ==========================================
// RUTE APLIKASI (Hanya bisa diakses jika sudah login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', AdminDashboard::class)
        ->name('dashboard');

    Route::view('/suppliers', 'suppliers')
        ->name('suppliers');

    Route::view('/criteria', 'criteria')
        ->name('criteria');

    Route::view('/results', 'results')
        ->name('results');

    Route::view('/profile', 'profile')
        ->name('profile');

    Route::get('/suppliers/{id}/values', function ($id) {
        return view('supplier-values', ['supplierId' => (int) $id]);
    })->name('supplier.values');

    // =====================================================
    // SECURITY DASHBOARD — Hanya untuk Admin
    // =====================================================
    Route::get('/security', \App\Livewire\SecurityDashboard::class)
        ->middleware('admin')
        ->name('security');

});

// Memuat rute-rute login/register bawaan dari Laravel Breeze
require __DIR__.'/auth.php';