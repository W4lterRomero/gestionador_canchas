<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;

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

// Página de Reportes
Route::get('/administracion/reportes', function () {
    return view('administracion.reportes');
})->name('administracion.reportes');

// USUARIOS
Route::get('/usuarios', function () {
    return view('usuarios.index');
})->name('usuarios.index');

Route::get('/usuarios/ver', function () {
    return view('usuarios.ver');
})->name('usuarios.ver');

Route::get('/usuarios/editar', function () {
    return view('usuarios.editar');
})->name('usuarios.editar');

// Livewire Counter (opcional)
Route::get('/counter', Counter::class);
