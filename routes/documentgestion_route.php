<?php

use App\Support\RouteDefinitions\DocumentGestionRoutes;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'checkPlatformWeb'])->group(function () {
    foreach (DocumentGestionRoutes::all() as $route) {
        $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
            ->name($route['name']);
        if ($route['permission']) {
            $routeInstance->middleware('permission:' . $route['name']);
        }
    }
    
});
