<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckPlatformMovil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {   
        if (Auth::guard('sanctum')->check()) {
            // Si el usuario está autenticado con un token válido, permitir que la solicitud continúe
            return $next($request);
        } else {
            // Si el token no es válido o no existe, devolver un error JSON
            return $request->expectsJson() 
                ? response()->json(['error' => 'Token no valido.'], 401)
                : redirect()->route('login');
        }
    }
}
