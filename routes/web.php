<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home.index');
    } else {
        return Inertia::render('Auth/Login');
    }
})->name('home');

Route::get('/test_test', [ProfileController::class, 'allfine']);

Route::middleware(['auth', 'checkPlatformWeb'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    include_once 'user_admin_route.php';
    include_once 'human_resource_route.php';
    include_once 'inventory_route.php';
    include_once 'project_route.php';
    include_once 'shopping_area_route.php';
    include_once 'finance_route.php';
    include_once 'huawei_route.php';
    include_once 'cicsa_route.php';
    include_once 'fleet_car_route.php';
    include_once 'room_rental_route.php';
    include_once 'share_point.php';
    include_once 'constant_route.php';
    require 'documentgestion_route.php';
});




// Route::get('/huaweiproject/{project}', [FileDataController::class, 'render'])->name('huawei.show');
// Route::post('/huawei_prices', [HuaweiController::class, 'store'])->name('huawei.post');
// Route::post('/huaweiproject/{project}/filter', [FileDataController::class, 'filter'])->name('huawei.filter');
// Route::put('/huaweiproject/{project}/{itemToEdit}/update', [FileDataController::class, 'updateRegister'])->name('huawei.put');
// Route::get('/huaweiproject/export/excel', [ExportController::class, 'export'])->name('huawei.export');


require __DIR__ . '/auth.php';
