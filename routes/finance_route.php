<?php

use Illuminate\Support\Facades\Route;
use App\Support\RouteDefinitions\FinanceRoutes;

foreach (FinanceRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}



