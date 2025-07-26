<?php

use App\Http\Controllers\Cicsa\CicsaController;
use Illuminate\Support\Facades\Route;
use App\Support\RouteDefinitions\CicsaRoutes;

foreach (CicsaRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}

// Route::post('/cicsa_purchase_order_validation/store/{cicsa_validation_id}', [CicsaController::class, 'updateOCValidation'])->name('cicsa.purchase_orders.validation.update');


