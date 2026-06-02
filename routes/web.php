<?php

use Illuminate\Support\Facades\Route;

Route::view('/dashboard', 'dashboard')
    ->name('dashboard');

Route::view('/suppliers', 'suppliers')
    ->name('suppliers');

Route::view('/criteria', 'criteria')
    ->name('criteria');

Route::view('/results', 'results')
    ->name('results');

Route::get('/suppliers/{id}/values', function ($id) {
    return view('supplier-values', ['supplierId' => (int) $id]);
})->name('supplier.values');


