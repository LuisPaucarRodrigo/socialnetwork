<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlatformMovil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user) {
            if ($this->isValidPlatform($user->platform)) {
                return $next($request); 
            } else {
                return response()->json(['error' => 'Acceso no autorizado.'], 403);
            }
        }
        return response()->json(['error' => 'Usuario no Autenticado.'], 401);
    }


    /**
     * Verificar si la plataforma del usuario es válida.
     *
     * @param  string  $platform
     * @return bool
     */
    private function isValidPlatform($platform)
    {
        return in_array($platform, ['Móvil', 'Web/Movil']);
    }
}
