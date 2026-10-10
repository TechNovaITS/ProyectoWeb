<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        if ($request->user() && $request->user()->rol === $rol) {
            return $next($request);
        }

        // Si no cumple, puedes redirigir o lanzar un error 403 (No autorizado)
        abort(403, 'No tienes permisos para acceder a esta sección.');
    }
    
}
