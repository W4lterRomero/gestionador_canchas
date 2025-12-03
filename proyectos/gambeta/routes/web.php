<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BloqueoHorarioController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\CanchaPrecioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EmployeeController;

// Página de Login (para usuarios no autenticados)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('inicio');
    }
    return view('login');
})->name('login');

// Deshabilitamos las rutas de registro y password de Auth::routes()
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// ===== RUTAS PARA EMPLEADOS Y ADMIN (ambos tienen acceso) =====
Route::middleware(['auth', 'role:empleado'])->group(function () {
    // Página de inicio para usuarios autenticados
    Route::get('/inicio', function () {
        return view('index');
    })->name('inicio');
    
    // Estadios - acceso para empleados y admin
    Route::get('/estadios', [EmployeeController::class, 'estadios'])
        ->name('estadios.index');
    
    Route::get('/estadios/{id}', function ($id) {
        return view('estadios.detalles', ['id' => $id]);
    })->name('estadios.detalles');
    
    Route::get('/reservar', function () {
        return view('estadios.reservar');
    })->name('estadios.reservar');

    // Reservas - acceso para empleados y admin
    Route::get('/reservas', function () {
        return view('reservas.index');
    })->name('reservas.index');
    
    Route::get('/reservas/editar', function () {
        return view('reservas.editar');
    })->name('reservas.editar');
});

// ===== RUTAS SOLO PARA ADMIN =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])
        ->name('admin.index');
    
    // CRUD de Canchas y recursos (solo admin)
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

        Route::post('/usuarios', [AdminUserController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [AdminUserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [AdminUserController::class, 'destroy'])->name('usuarios.destroy');
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

// Creditos
    Route::get('/creditos', function () {
        return view('creditos');
    })->name('creditos');