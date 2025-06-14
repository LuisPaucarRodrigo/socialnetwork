<?php

use App\Support\RouteDefinitions\FleetCarRoutes;
use Illuminate\Support\Facades\Route;

foreach (FleetCarRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}
