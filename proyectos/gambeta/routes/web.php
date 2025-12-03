<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BloqueoHorarioController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\CanchaPrecioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClienteController;

// Login page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('inicio');
    }
    return view('login');
})->name('login');

// Disable default auth routes we don't need
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// Shared routes for staff
Route::middleware(['auth', 'role:empleado'])->group(function () {
    // Dashboard
    Route::get('/inicio', function () {
        return view('index');
    })->name('inicio');
    
    // Stadiums
    Route::get('/estadios', [EmployeeController::class, 'estadios'])
        ->name('estadios.index');
    
    Route::get('/estadios/{id}', function ($id) {
        return view('estadios.detalles', ['id' => $id]);
    })->name('estadios.detalles');
    
    Route::get('/reservar', function () {
        return view('estadios.reservar');
    })->name('estadios.reservar');

    // Reservations
    Route::get('/reservas', function () {
        return view('reservas.index');
    })->name('reservas.index');

    Route::get('/reservas/editar', function () {
        return view('reservas.editar');
    })->name('reservas.editar');

    // Clientes - acceso para empleados y admin
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{cliente}/historial', [ClienteController::class, 'historial'])->name('clientes.historial');
    Route::post('/clientes/{cliente}/toggle-frecuente', [ClienteController::class, 'toggleFrecuente'])->name('clientes.toggle-frecuente');
    Route::post('/clientes/{cliente}/actualizar-estadisticas', [ClienteController::class, 'actualizarEstadisticas'])->name('clientes.actualizar-estadisticas');
});

// Admin Only Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])
        ->name('admin.index');
    
    // Resource Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/canchas', [CanchaController::class, 'store'])->name('canchas.store');
        Route::put('/canchas/{cancha}', [CanchaController::class, 'update'])->name('canchas.update');
        Route::delete('/canchas/{cancha}', [CanchaController::class, 'destroy'])->name('canchas.destroy');
        Route::delete('/canchas/imagenes/{imagen}', [CanchaController::class, 'destroyImagen'])->name('canchas.imagenes.destroy');
        
        Route::post('/bloqueos', [BloqueoHorarioController::class, 'store'])->name('bloqueos.store');
        Route::put('/bloqueos/{bloqueo}', [BloqueoHorarioController::class, 'update'])->name('bloqueos.update');
        Route::delete('/bloqueos/{bloqueo}', [BloqueoHorarioController::class, 'destroy'])->name('bloqueos.destroy');
        
        Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
        Route::put('/reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
        Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
        
        Route::post('/precios', [CanchaPrecioController::class, 'store'])->name('precios.store');
        Route::put('/precios/{precio}', [CanchaPrecioController::class, 'update'])->name('precios.update');
        Route::delete('/precios/{precio}', [CanchaPrecioController::class, 'destroy'])->name('precios.destroy');

        Route::post('/usuarios', [AdminUserController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [AdminUserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [AdminUserController::class, 'destroy'])->name('usuarios.destroy');

        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    });

    // Counter (opcional)
    Route::get('/counter', function () {
        return app(Counter::class);
    });
});

// Rutas de autenticación
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])
    ->name('admin.login');
Route::post('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth');
