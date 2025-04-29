<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

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
            // $permissions = $user->onePermission();
            
            // Inertia::share('userPermissions', $permissions);
        }

        return $next($request);
    }
}
