<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\CanchaController;

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

Route::get('/reservar', function () {
    return view('estadios.reservar');
})->name('estadios.reservar');

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

// Livewire Counter (opcional)
Route::get('/counter', Counter::class);
