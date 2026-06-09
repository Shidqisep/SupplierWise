<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CriteriaController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SupplierValueController;
use Illuminate\Support\Facades\Route;

// Route::apiResource('categories', CategoryController::class);
// Route::apiResource('criteria', CriteriaController::class);
// Route::apiResource('suppliers', SupplierController::class);
// Route::apiResource('supplier-values', SupplierValueController::class);

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
// Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::post('/login/google', [\App\Http\Controllers\Api\AuthController::class, 'googleLogin']);

// Endpoint perhitungan COPRAS — GET /api/results?category_id={id}
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('criteria', CriteriaController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('supplier-values', SupplierValueController::class);
    
    // Endpoint perhitungan COPRAS
    Route::get('results', [ResultController::class, 'calculate']);
});
    