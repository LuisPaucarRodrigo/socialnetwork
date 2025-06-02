<?php

use App\Http\Controllers\ProjectArea\PextController;
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

    Route::get('/project/project_additionals/expenses/{project_id}', [PextController::class, 'additional_project_expensese'])->name('projectmanagement.projectadditional.expenses');