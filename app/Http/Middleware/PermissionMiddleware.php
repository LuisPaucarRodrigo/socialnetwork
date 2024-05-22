<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // public function handle(Request $request, Closure $next, $role): Response
    // {
    //     if (Auth::check() && Auth::user()->hasRole($role)) {
    //         return $next($request);
    //     }
    //     abort(403, 'No tienes el rol requerido para acceder.');
    // }

    public function handle($request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
            return redirect('/login');
        }
        $user = auth()->user();
        // Verificar si el usuario tiene el permiso necesario
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        if ($user->hasPermission('UserManager')) {
            return redirect('/users');
        } elseif ($user->hasPermission('HumanResourceManager')) {
            return redirect('/management_employees/index');
        } elseif ($user->hasPermission('FinanceManager')) {
            return redirect('/finance/expencemanagement');
        } elseif ($user->hasPermission('InventoryManager')) {
            return redirect('/finance/expencemanagement');
        } elseif ($user->hasPermission('ProjectManager')) {
            return redirect('/projectmanagement');
        } elseif ($user->hasPermission('PurchasingManager')) {
            return redirect('/shopping_area/purchasesrequest');
        } elseif ($user->hasPermission('Administration')) {
            return redirect('/management_employees');
        } elseif ($user->hasPermission('DocumentGestion')) {
            return redirect(route('documment.management.folders'));
        } elseif ($user->hasPermission('SocialNetwork')) {
            return redirect(route('socialnetwork.sot'));
        } else {
            abort(403, 'No tienes el rol requerido para acceder.');
        }
    }
}
