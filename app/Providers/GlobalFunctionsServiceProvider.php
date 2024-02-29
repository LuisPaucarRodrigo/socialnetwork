<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GlobalFunctionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public static function hasAllPermissions()
            {
                $user_id = Auth::id();
                $user = User::with('role.permissions')->find($user_id);

                $permissions = $user->role->permissions->pluck('id')->toArray();
                $all_permissions_exist = true;

                for ($i = 1; $i <= 11; $i++) {
                    if (!in_array($i, $permissions)) {
                        $all_permissions_exist = false;
                        break;
                    }
                }

                return $all_permissions_exist;
            }
}
