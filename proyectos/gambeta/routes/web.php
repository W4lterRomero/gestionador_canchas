<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\BloqueoHorarioController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\CanchaPrecioController;
use App\Http\Controllers\ReservaController;

// Página de Login
Route::get('/', function () {
    return view('login');
})->name(name: 'login');

// Página principal
Route::get('/home', function () {
    return view('index');
})->name('home');

// Página de Estadios
Route::get('/estadios', function () {
    return view('estadios.index');
})->name('estadios.index');

Route::get('/estadios/detalles', function () {
    return view('estadios.detalles');
})->name('estadios.detalles');


// Página de Reservas
Route::get('/reservas', function () {
    return view('reservas.index');
})->name('reservas.index');

Route::get('/reservas/editar', function () {
    return view('reservas.editar');
})->name('reservas.editar');

// Página de Administración
Route::get('/admin', function () {
    return view('administracion.index');
})->name('admin.index');

// Página de Administración y CRUD de Canchas
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [CanchaController::class, 'index'])->name('index');
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

// Livewire Counter (opcional)
Route::get('/counter', Counter::class);
