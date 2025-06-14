<?php

use App\Http\Controllers\Services\SharePointController;
use App\Support\RouteDefinitions\SharePointRoutes;
use Illuminate\Support\Facades\Route;

foreach (SharePointRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}