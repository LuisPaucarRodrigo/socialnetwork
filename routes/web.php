<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return Inertia::location(route('users.index'));
    } else {
        return Inertia::render('Auth/Login');
    }
})->name('home');

Route::get('/test_test', [ProfileController::class,'allfine']);

Route::middleware(['auth', 'checkPlatformWeb'])->group(function () { 
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    
    require 'user_admin_route.php';
    require 'human_resource_route.php';
    require 'inventory_route.php';
    require 'project_route.php';
    require 'shopping_area_route.php';
    require 'finance_route.php';
    require 'huawei_route.php';
    require 'cicsa_route.php';
    require 'fleet_car_route.php';
    require 'documentgestion_route.php';
});




// Route::get('/huaweiproject/{project}', [FileDataController::class, 'render'])->name('huawei.show');
// Route::post('/huawei_prices', [HuaweiController::class, 'store'])->name('huawei.post');
// Route::post('/huaweiproject/{project}/filter', [FileDataController::class, 'filter'])->name('huawei.filter');
// Route::put('/huaweiproject/{project}/{itemToEdit}/update', [FileDataController::class, 'updateRegister'])->name('huawei.put');
// Route::get('/huaweiproject/export/excel', [ExportController::class, 'export'])->name('huawei.export');


require __DIR__ . '/auth.php';
