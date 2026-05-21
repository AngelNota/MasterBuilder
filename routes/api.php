<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComponentsApiController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/components/compatible', [ComponentsApiController::class, 'compatible']);
});

