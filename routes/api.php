<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CriteriaController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SupplierValueController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('criteria', CriteriaController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('supplier-values', SupplierValueController::class);

// Endpoint perhitungan COPRAS — GET /api/results?category_id={id}
Route::get('results', [ResultController::class, 'calculate']);
