<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.signin');
        }

        $userRole = Auth::user()->rol;
        $routeName = $request->route()?->getName();

        // ADMIN intentando entrar al fichaje de empleados
        if ($userRole === 'admin' && $routeName === 'fichaje') {
            return redirect()->route('empresas');
        }

        // ENCARGADO → solo terminal
        if ($userRole === 'encargado') {
            if (!str_starts_with($routeName, 'terminal.')) {
                return redirect()->route('terminal.fichaje');
            }
            return $next($request);
        }

        // Otros roles: validar acceso normal
        if (!in_array($userRole, $roles)) {
            return redirect()->route('root');
        }

        return $next($request);
    }
}
