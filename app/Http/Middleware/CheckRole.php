<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja las peticiones entrantes verificando los roles del usuario.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verifica si el usuario está autenticado, tiene alguno de los roles permitidos y está activo
        if (! $request->user() || ! $request->user()->hasRole(...$roles) || ! $request->user()->is_active) {
            abort(403, 'Acceso denegado: No tienes permisos suficientes para acceder a este módulo.');
        }

        return $next($request);
    }
}