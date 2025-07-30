<?php

use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\AdministrativeCostsController;
use App\Http\Controllers\ProjectArea\Pext\PextController;
use Illuminate\Support\Facades\Route;
use App\Support\RouteDefinitions\ProjectRoutes;

//new permissions routes
foreach (ProjectRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}
