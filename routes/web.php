<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    if (auth()->check()) {
        return Inertia::location(route('users.index'));
    } else {
        return Inertia::render('Auth/Login');
    }
})->name('home');


Route::get('/scrape', [ScraperController::class, 'scrape']);

Route::middleware(['auth', 'checkPlatformWeb'])->group(function () { 
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/login/google', [GoogleAuthController::class, 'redirectToGoogle']);
    Route::get('/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

    include_once 'user_admin_route.php';
    include_once 'human_resource_route.php';
    include_once 'inventory_route.php';
    include_once 'project_route.php';
    include_once 'shopping_area_route.php';
    include_once 'finance_route.php';
    include_once 'huawei_route.php';
    include_once 'cicsa_route.php';
    include_once 'fleet_car_route.php';
});


Route::middleware(['auth', 'permission:DocumentGestion', 'checkPlatformWeb'])->group(function () {
    include_once 'documentgestion_route.php';
});

// Route::get('/huaweiproject/{project}', [FileDataController::class, 'render'])->name('huawei.show');
// Route::post('/huawei_prices', [HuaweiController::class, 'store'])->name('huawei.post');
// Route::post('/huaweiproject/{project}/filter', [FileDataController::class, 'filter'])->name('huawei.filter');
// Route::put('/huaweiproject/{project}/{itemToEdit}/update', [FileDataController::class, 'updateRegister'])->name('huawei.put');
// Route::get('/huaweiproject/export/excel', [ExportController::class, 'export'])->name('huawei.export');


require __DIR__ . '/auth.php';
