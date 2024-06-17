<?php

use App\Exports\HuaweiExport;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Huawei\FileDataController;
use App\Http\Controllers\Huawei\ExportController;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return Inertia::location(route('users.index'));
    } else {
        return Inertia::render('Auth/Login');
    }
})->name('home');

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

Route::get('/huawei/{project}', [FileDataController::class, 'render'])->name('huawei.show');
Route::post('/huawei/{project}/post', [FileDataController::class, 'uploadExcel'])->name('huawei.post');
Route::post('/huawei/{project}/filter', [FileDataController::class, 'filter'])->name('huawei.filter');
Route::put('/huawei/{project}/{itemToEdit}/update', [FileDataController::class, 'updateRegister'])->name('huawei.put');
Route::get('/huawei/export/excel', [ExportController::class, 'export'])->name('huawei.export');

require __DIR__ . '/auth.php';
