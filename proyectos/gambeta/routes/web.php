<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\BloqueoHorarioController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\CanchaPrecioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EmployeeController;

// Página de Login
Route::get('/', function () {
    return view('login');
})->name('login');

Auth::routes();

// Página principal (redirige según rol)
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])
    ->name('admin.index')  
    ->middleware(['auth', 'role:admin']);


// ===== RUTAS PARA EMPLEADOS (employee o admin) =====
Route::middleware(['auth'])->group(function () {
    Route::get('/estadios', [EmployeeController::class, 'estadios'])
        ->name('estadios.index');
    
    Route::get('/reservar', function () {
        return view('estadios.reservar');
    })->name('estadios.reservar');

});

// ===== RUTAS SOLO PARA ADMIN =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Reservas
    Route::get('/reservas', function () {
        return view('reservas.index');
    })->name('reservas.index');
    
    Route::get('/reservas/editar', function () {
        return view('reservas.editar');
    })->name('reservas.editar');

    // Admin Dashboard (consolidado)
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])
        ->name('admin.index');
    
    // CRUD de Canchas y recursos
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/canchas', [CanchaController::class, 'store'])->name('canchas.store');
        Route::put('/canchas/{cancha}', [CanchaController::class, 'update'])->name('canchas.update');
        Route::delete('/canchas/{cancha}', [CanchaController::class, 'destroy'])->name('canchas.destroy');
        
        Route::post('/bloqueos', [BloqueoHorarioController::class, 'store'])->name('bloqueos.store');
        Route::put('/bloqueos/{bloqueo}', [BloqueoHorarioController::class, 'update'])->name('bloqueos.update');
        Route::delete('/bloqueos/{bloqueo}', [BloqueoHorarioController::class, 'destroy'])->name('bloqueos.destroy');
        
        Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
        Route::put('/reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
        Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
        
        Route::post('/precios', [CanchaPrecioController::class, 'store'])->name('precios.store');
        Route::put('/precios/{precio}', [CanchaPrecioController::class, 'update'])->name('precios.update');
        Route::delete('/precios/{precio}', [CanchaPrecioController::class, 'destroy'])->name('precios.destroy');
    });

    // Counter (opcional)
    Route::get('/counter', function () {
        return app(Counter::class);
    });
});

// Rutas de autenticación admin (si las necesitas separadas)
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])
    ->name('admin.login');
Route::post('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth');