<?php

use App\Support\RouteDefinitions\UserAdminRoutes;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkPlatformWeb'])->group(function () {
    foreach (UserAdminRoutes::all() as $route) {
        $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
            ->name($route['name']);
        if ($route['permission']) {
            $routeInstance->middleware('permission:' . $route['name']);
        }
    }
});


