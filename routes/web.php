<?php

use App\Exports\HuaweiExport;
use App\Http\Controllers\Huawei\HuaweiProjectController;
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

// Route::get('/huaweiproject/{project}', [FileDataController::class, 'render'])->name('huawei.show');
// Route::post('/huawei_prices', [HuaweiController::class, 'store'])->name('huawei.post');
// Route::post('/huaweiproject/{project}/filter', [FileDataController::class, 'filter'])->name('huawei.filter');
// Route::put('/huaweiproject/{project}/{itemToEdit}/update', [FileDataController::class, 'updateRegister'])->name('huawei.put');
// Route::get('/huaweiproject/export/excel', [ExportController::class, 'export'])->name('huawei.export');

Route::get('/huaweiLoads', [HuaweiController::class, 'show'])->name('huawei.loads');
Route::post('/huaweiLoads/import', [HuaweiController::class, 'import'])->name('huawei.loads.import');
Route::get('/huaweiLoads/{loadId}/products/{noPg?}', [HuaweiController::class, 'renderByLoad'])->name('huawei.loads.products');
Route::get('/huaweiLoads/products/{huawei_product}/similarity', [HuaweiController::class, 'searchSimilarities'])->name('huawei.loads.products.similarities');
Route::put('/huaweiLoads/{loadId}/products/associate/{huawei_product}', [HuaweiController::class, 'associate'])->name('huawei.loads.products.associate');
Route::get('/huaweiLoads/{loadId}/exportpdf', [HuaweiController::class, 'exportHuaweiProducts'])->name('huawei.loads.exportpdf');

Route::get('/huawei/inventory/{equipment?}', [HuaweiManagementController::class, 'show'])->name('huawei.inventory.show');
Route::get('/huawei/inventory/search/{request}/{equipment?}', [HuaweiManagementController::class, 'searchIndex'])->name('huawei.inventory.show.search');
Route::get('/huawei/inventory/create/get', [HuaweiManagementController::class, 'create'])->name('huawei.inventory.create');
Route::post('/huawei/inventory/create/post', [HuaweiManagementController::class, 'store'])->name('huawei.inventory.store');
Route::post('/huawei/inventory/create/get/brand_post', [HuaweiManagementController::class, 'storeBrand'])->name('huawei.inventory.create.brand');
Route::post('/huawei/inventory/create/get/brand_model_post', [HuaweiManagementController::class, 'storeBrandModel'])->name('huawei.inventory.create.brandmodel');
Route::get('/huawei/inventory/details/{id}/{equipment?}', [HuaweiManagementController::class, 'showDetails'])->name('huawei.inventory.show.details');
Route::get('/huawei/inventory/details/search/{id}/{request}/{equipment?}', [HuaweiManagementController::class, 'search'])->name('huawei.inventory.show.details.search');
Route::post('/huawei/inventory/details/refunds/post/{equipment?}', [HuaweiManagementController::class, 'refund'])->name('huawei.inventory.details.refund');
Route::get('/huawei/inventory/refunds/get/{equipment?}', [HuaweiManagementController::class, 'getRefunds'])->name('huawei.inventory.refunds');
Route::get('/huawei/inventory/refunds/search/{request}/{equipment?}', [HuaweiManagementController::class, 'searchRefunds'])->name('huawei.inventory.refunds.search');

Route::get('huawei/projects/show', [HuaweiProjectController::class, 'show'])->name('huawei.projects');
Route::get('huawei/projects/create', [HuaweiProjectController::class, 'create'])->name('huawei.projects.create');
Route::post('huawei/projects/store', [HuaweiProjectController::class, 'store'])->name('huawei.projects.store');
Route::get('huawei/projects/toUpdate/{huawei_project}', [HuaweiProjectController::class, 'toUpdate'])->name('huawei.projects.toupdate');
Route::put('huawei/projects/update/{huawei_project}', [HuaweiProjectController::class, 'update'])->name('huawei.projects.update');
Route::delete('huawei/projects/deleteemployee/{id}/delete', [HuaweiProjectController::class, 'deleteEmployee'])->name('huawei.projects.deleteemployee');
Route::post('huawei/projects/addemployee/{huawei_project}/add', [HuaweiProjectController::class, 'add_employee'])->name('huawei.projects.addemployee');

require __DIR__ . '/auth.php';
