<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CalculationController;
use App\Http\Controllers\Api\OriginController;
use App\Http\Controllers\Api\DestinationController;

// Endpoint untuk menjalankan mesin algoritma EDAS + Haversine
Route::post('/calculate-edas', [CalculationController::class, 'calculateEdas']);

// Rute untuk mengelola data Gudang
Route::get('/origins', [OriginController::class, 'index']);
Route::post('/origins', [OriginController::class, 'store']);
// Rute untuk mengelola data Posko
Route::get('/destinations', [DestinationController::class, 'index']);
Route::post('/destinations', [DestinationController::class, 'store']);
Route::delete('/destinations/{id}', [DestinationController::class, 'destroy']);

