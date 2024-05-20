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
        // Obtener el usuario autenticado
        $user = $request->user();

        // Verificar si el usuario está autenticado y su plataforma es válida
        if ($user && $this->isValidPlatform($user->platform)) {
            return $next($request);
        }

        // Si la plataforma no es válida, redirigir o responder con un error
        return response()->json(['error' => 'Acceso no autorizado.'], 403);
    }

    /**
     * Verificar si la plataforma del usuario es válida.
     *
     * @param  string  $platform
     * @return bool
     */
    private function isValidPlatform($platform)
    {
        // Aquí defines la lógica para determinar si la plataforma es válida
        // Por ejemplo, si la plataforma es 'web', 'móvil' o 'web/móvil'
        return in_array($platform, ['Móvil', 'Web/Móvil']);
    }
}
