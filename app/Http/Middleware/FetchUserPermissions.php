<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class FetchUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    
    public function handle($request, Closure $next)
    {

        $user = auth()->user();
        if ($user) {
            $functionalities = $user->role->functionalities->pluck('key_name');
            Log::info($functionalities);
            $permissions = $user->onePermission();
            Inertia::share('userFunctionalities', $functionalities);
            Inertia::share('userPermissions', $permissions);
        }

        return $next($request);
    }
}
