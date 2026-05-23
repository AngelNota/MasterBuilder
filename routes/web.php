<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompatibilityController;

// 1. La página de inicio pública (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Grupo de rutas protegidas (Solo usuarios logueados)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Nuestro Panel de Control principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'index'])->name('profile.edit');

    // Ruta clásica MVC para las cotizaciones (Accesible para clientes y admins)
    Route::get('/cotizaciones/{cotizacione}/pdf', [QuoteController::class, 'downloadPdf'])->name('cotizaciones.pdf');
    Route::post('/cotizaciones/{cotizacione}/reenviar', [QuoteController::class, 'resendEmail'])->name('cotizaciones.reenviar');
    Route::resource('/cotizaciones', QuoteController::class);

    // Ruta para verificar compatibilidad con IA
    Route::post('/ai/check-compatibility', [CompatibilityController::class, 'check'])->name('ai.compatibility');

    // Rutas exclusivas para el Administrador (CRUD de componentes y categorías)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('/inventario', InventoryController::class);
        Route::resource('/categorias', CategoryController::class);
    });
});

// 3. Incluir las rutas de configuración adicionales (si existen)
if (file_exists(__DIR__.'/settings.php')) {
    require __DIR__.'/settings.php';
}