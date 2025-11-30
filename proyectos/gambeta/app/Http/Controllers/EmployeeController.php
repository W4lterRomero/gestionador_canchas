<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // El middleware de las rutas ya maneja la autenticación y roles
        $this->middleware('auth');
    }

    /**
     * Estadios page accessible to employees and admins
     */
    public function estadios()
    {
        return view('estadios.index');
    }
}