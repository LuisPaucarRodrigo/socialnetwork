<?php

use App\Http\Controllers\Services\SharePointController;
use Illuminate\Support\Facades\Route;
use App\Support\RouteDefinitions\ProjectRoutes;

Route::get('/sharepoint/index_cost_line', [SharePointController::class, 'index'])->name('sharepoint.index');

//new permissions routes
foreach (ProjectRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}
