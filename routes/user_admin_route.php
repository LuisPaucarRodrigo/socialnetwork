<?php

use App\Support\RouteDefinitions\UserAdminRoutes;

Route::middleware(['auth', 'checkPlatformWeb'])->group(function () { 
    foreach (UserAdminRoutes::all() as $route) {
        $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
            ->name($route['name']);
        if ($route['permission']) {
            $routeInstance->middleware('permission:' . $route['name']);
        }
    }
});


