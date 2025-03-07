<?php

namespace App\Http\Middleware;

use App\Constants\RolesConstants;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request)
    {
        $user = $request->user();
        $userModules = [];
        $userSubmodules = [];
        if($user) {
            $userPermissions = $user->load('role.permissions')->role->permissions;
            foreach (RolesConstants::MODULES as $module) {
                foreach (constant("\\App\\Constants\\RolesConstants::$module") as $perm) { 
                    if ($userPermissions->contains('name', $perm)) {
                        array_push($userModules, $module); break;
                    }
                }
            }
            foreach (RolesConstants::SUBMODULES as $subm) {
                foreach (constant("\\App\\Constants\\RolesConstants::$subm") as $perm) { 
                    if ($userPermissions->contains('name', $perm)) {
                        array_push($userSubmodules, $subm); break;
                    }
                }
            }
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'userModules' => $userModules,
                'userSubmodules' => $userSubmodules,
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                ];
            },
            'modules' => RolesConstants::MODULES,
            'submodules' => RolesConstants::SUBMODULES,
            'showingMobileMenu' => false,
        ]);
    }
}
