<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\QuoteController;

// 1. La página de inicio pública (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Grupo de rutas protegidas (Solo usuarios logueados)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Nuestro Panel de Control principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/inventario', InventoryController::class);
    
    // Ruta clásica MVC para las cotizaciones
    Route::resource('/cotizaciones', QuoteController::class);
});
    // Ruta clásica MVC para el inventario
    Route::resource('/inventario', InventoryController::class);
});
    Route::get('/profile', [DashboardController::class, 'index'])->name('profile.edit');
});

// 3. Incluir las rutas de configuración adicionales (si existen)
if (file_exists(__DIR__.'/settings.php')) {
    require __DIR__.'/settings.php';
}