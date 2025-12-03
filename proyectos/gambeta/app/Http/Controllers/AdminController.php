<?php

namespace App\Http\Controllers;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\CanchaPrecio;
use App\Models\Reserva;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Panel de administración (requiere auth)
     */
    public function index()
    {
        $canchas = Cancha::orderBy('nombre')->get();
        $reservas = Reserva::with(['cancha', 'cliente', 'creador', 'actualizador'])
            ->latest('fecha_reserva')
            ->get();
        $bloqueos = BloqueoHorario::with(['cancha', 'creador'])
            ->latest('fecha_inicio')
            ->get();
        $precios = CanchaPrecio::with('cancha')
            ->latest('fecha_desde')
            ->get();
        $usuarios = User::with('role')
            ->orderBy('name')
            ->get();
        $roles = Role::orderBy('name')->get();

        return view('administracion.index', compact('canchas', 'reservas', 'bloqueos', 'precios', 'usuarios', 'roles'));
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
