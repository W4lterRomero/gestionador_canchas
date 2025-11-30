<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Panel de administración (requiere auth)
     */
    public function index()
    {
        return view('administracion.index');
    }

    /**
     * Procesar login (NO requiere auth)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = auth()->user();
            
            // Redirigir según rol
            if ($user->isAdmin()) {
                return redirect()->route('admin.index');
            }
            
            // Empleados van a inicio
            return redirect()->route('inicio');
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión (requiere auth)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}