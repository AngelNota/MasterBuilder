<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComponentsApiController;
use App\Http\Controllers\Api\QuotesApiController;

// Rutas públicas
Route::get('/components', [ComponentsApiController::class, 'index']);
Route::get('/components/{id}', [ComponentsApiController::class, 'show']);
Route::get('/categories', [ComponentsApiController::class, 'categories']);

// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/components/compatible', [ComponentsApiController::class, 'compatible']);
    Route::post('/quotes', [QuotesApiController::class, 'store']);
});

