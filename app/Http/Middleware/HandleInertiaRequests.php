<?php

namespace App\Http\Middleware;

use App\Constants\RolesConstants;
use App\Models\Module;
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
        $modules = Module::where('type', 'module');
        $submodules = Module::where('type', 'submodule');

        if ($user?->role?->id===1) {
            $userModules = $modules->pluck('name');
            $userSubModules = $submodules->pluck('name');
        }
        else  {
            $userModules = $user?->role?->getCurrentModules()['modules'];
            $userSubModules = $user?->role?->getCurrentModules()['submodules'];;
        }
        
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'userModules' => $userModules,
                'userSubModules' => $userSubModules,
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                ];
            },
            'modules' => $modules->pluck('name', 'name'),
            'submodules' => $submodules->pluck('name', 'name'),
            'showingMobileMenu' => false,
        ]);
    }
}
