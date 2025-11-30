<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Verificar si es admin (PRIMERO)
        if ($user->hasRole('admin') || $user->hasRole('Administrador') || $user->hasRole('administrator')) {
            return redirect()->route('admin.index');
        }
        
        // Verificar si es empleado
        if ($user->hasRole('empleado') || $user->hasRole('Empleado') || $user->hasRole('employee')) {
            return redirect()->route('estadios.index');
        }
        
        // Usuario regular
        return redirect()->route('estadios.reservar');
    }
}