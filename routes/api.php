<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CriteriaController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SupplierValueController;
use Illuminate\Support\Facades\Route;

// =====================================================
// RUTE PUBLIK — Dengan Rate Limiting Anti-DDoS
// =====================================================
// Login & Register dibatasi 5 percobaan/menit per IP
// untuk mencegah serangan brute force
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/login/google', [\App\Http\Controllers\Api\AuthController::class, 'googleLogin']);
});

// =====================================================
// RUTE TERPROTEKSI — Memerlukan Token Sanctum
// =====================================================
// Rate limit: 60 request/menit (dikonfigurasi via throttle:api)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('criteria', CriteriaController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('supplier-values', SupplierValueController::class);
    
    // Endpoint perhitungan COPRAS
    Route::get('results', [ResultController::class, 'calculate']);
});