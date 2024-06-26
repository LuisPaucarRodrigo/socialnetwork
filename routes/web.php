<?php

use App\Exports\HuaweiExport;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Huawei\FileDataController;
use App\Http\Controllers\Huawei\ExportController;
use App\Http\Controllers\Inventory\HuaweiController;
use App\Http\Controllers\Huawei\HuaweiManagementController;
use Inertia\Inertia;
use App\Http\Controllers\ScraperController;
use Illuminate\Routing\Route as RoutingRoute;

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

    include_once 'user_admin_route.php';
    include_once 'human_resource_route.php';
    include_once 'inventory_route.php';
    include_once 'project_route.php';
    include_once 'shopping_area_route.php';
    include_once 'finance_route.php';
});


Route::middleware(['auth', 'permission:DocumentGestion', 'checkPlatformWeb'])->group(function () {
    include_once 'documentgestion_route.php';
});

Route::middleware(['auth', 'permission:SocialNetwork', 'checkPlatformWeb'])->group(function () {
    include_once 'snsot_route.php';
});

Route::get('/huaweiproject/{project}', [FileDataController::class, 'render'])->name('huawei.show');
Route::post('/huawei_prices', [HuaweiController::class, 'store'])->name('huawei.post');
Route::post('/huaweiproject/{project}/filter', [FileDataController::class, 'filter'])->name('huawei.filter');
Route::put('/huaweiproject/{project}/{itemToEdit}/update', [FileDataController::class, 'updateRegister'])->name('huawei.put');
Route::get('/huaweiproject/export/excel', [ExportController::class, 'export'])->name('huawei.export');

Route::get('/huaweiLoads', [HuaweiController::class, 'show'])->name('huawei.loads');
Route::post('/huaweiLoads/import', [HuaweiController::class, 'import'])->name('huawei.loads.import');
Route::get('/huaweiLoads/{loadId}/products/{noPg?}', [HuaweiController::class, 'renderByLoad'])->name('huawei.loads.products');
Route::get('/huaweiLoads/products/{huawei_product}/similarity', [HuaweiController::class, 'searchSimilarities'])->name('huawei.loads.products.similarities');
Route::put('/huaweiLoads/{loadId}/products/associate/{huawei_product}', [HuaweiController::class, 'associate'])->name('huawei.loads.products.associate');
Route::get('/huaweiLoads/{loadId}/exportpdf', [HuaweiController::class, 'exportHuaweiProducts'])->name('huawei.loads.exportpdf');

Route::get('/huawei/inventory/{equipment?}', [HuaweiManagementController::class, 'show'])->name('huawei.inventory.show');
Route::get('/huawei/inventory/create/get', [HuaweiManagementController::class, 'create'])->name('huawei.inventory.create');

require __DIR__ . '/auth.php';
