<?php

namespace App\Http\Middleware;

use App\Models\Empleado;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckVendedorRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Obtener el usuario autenticado
            $usuario = Auth::user();

            // Verificar si el empleado existe y tiene rol 1
            if ($usuario && $usuario->rol === 2 || $usuario->rol === 1) {
                return $next($request); // Continuar con la solicitud si el rol es admin
            }
        }

        // Si no es administrador o no se encontró al empleado, retornamos un error
        return response()->json(['error' => 'Acceso no autorizado'], 403); // Código 403 para acceso denegado
    }
}
