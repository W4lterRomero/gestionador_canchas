<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        // Definir roles aceptados — soporta múltiples roles pasados al middleware
        $acceptedRoles = [];
        foreach ($roles as $role) {
            // Support pipe-separated in a single param too
            $parts = preg_split('/[|,]/', $role);
            foreach ($parts as $r) {
                $r = trim(strtolower($r));
                if ($r === 'admin' || $r === 'administrador' || $r === 'administrator') {
                    $acceptedRoles = array_merge($acceptedRoles, ['admin', 'Administrador', 'administrator']);
                } elseif ($r === 'employee' || $r === 'empleado') {
                    $acceptedRoles = array_merge($acceptedRoles, ['empleado', 'Empleado', 'employee', 'admin', 'Administrador', 'administrator']);
                } else {
                    $acceptedRoles[] = $r;
                }
            }
        }

        // Normalize to unique
        $acceptedRoles = array_values(array_unique($acceptedRoles));

        // Verificar si tiene alguno de los roles
        $hasAccess = false;
        foreach ($acceptedRoles as $roleName) {
            if ($user->hasRole($roleName)) {
                $hasAccess = true;
                break;
            }
        }
        
        if (!$hasAccess) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}