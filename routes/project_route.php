<?php

use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\AdministrativeCostsController;
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

//General expenses
Route::get('/project/getExpenses/{fixedOrAdditional}/general/{type}', [PextController::class, 'getExpenses'])->name('getExpenses.general');

//AdditionalCost
Route::get('/project/additional_Projects/{project_id}', [AdditionalCostsController::class, 'additionalProjects'])->name('additionalProjects');
Route::get('/project/administrative_costs/{month_project_id}', [AdministrativeCostsController::class, 'getAdminitrativeExpenses'])->name('getExpenses.administrativeCosts');
