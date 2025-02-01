<?php

use App\Http\Controllers\CarManagement\CarsController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:CarManager')->group(function () {
    Route::get('/fleet_cars/index', [CarsController::class, 'index'])->name('fleet.cars.index');
    Route::post('/fleet_cars/store', [CarsController::class, 'store'])->name('fleet.cars.store');
});
