<?php

namespace App\Http\Middleware;

use App\Models\Functionality;
use App\Models\FunctionalityPermission;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
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

    public function handle($request, Closure $next, $permissions)
    {
        if (!auth()->check()) {
            // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
            return redirect('login');
        }

        $user = auth()->user();
        if($user->role_id === 1) return $next($request);
        $functionalities = $user->role->functionalities()->get()->pluck('id');
        $permission = Permission::where('name', $permissions)->first();

        $hasOne = FunctionalityPermission::whereIn('functionality_id', $functionalities)
            ->where('permission_id', $permission->id)->first();
        if ($hasOne) {
            return $next($request);
        }
        
        //PAST
        // $permissionsArray = explode('|', $permissions);
        // foreach ($permissionsArray as $permission) {
            
        //     if ($user->hasPermission($permission)) {
        //         return $next($request);
        //     }
        // }

        // Redirecciones basadas en permisos específicos
        // $redirectRoutes = [
        //     'UserManager' => route('users.index'),
        //     'HumanResourceManager' => route('management.employees'),
        //     'HumanResource' => route('management.employees'),
        //     'FinanceManager' => route('managementexpense.index'),
        //     'Finance' => route('managementexpense.index'),
        //     'InventoryManager' => route('warehouses.warehouses'),
        //     'Inventory' => route('warehouses.warehouses'),
        //     'ProjectManager' => route('projectmanagement.index'),
        //     'Project' => route('projectmanagement.index'),
        //     'PurchasingManager' => route('purchasesrequest.index'),
        //     'Purchasing' => route('purchasesrequest.index'),
        //     'Administration' => '/management_employees',
        //     'DocumentGestion' => route('documment.management.folders'),
        //     'CarManager' => route('fleet.cars.index'),
        //     'Car' => route('fleet.cars.index'),
        //     'HuaweiManager' => route('huawei.inventory.show', ['warehouse' => 1])
        // ];

        // foreach ($redirectRoutes as $permission => $route) {
        //     if ($user->hasPermission($permission)) {
        //         return redirect($route);
        //     }
        // }

        abort(403, 'No tienes el rol requerido para acceder.');
    }
}
