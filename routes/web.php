<?php

use App\Http\Controllers\DocumentManagement\FolderController;
use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// use App\Http\Controllers\VacationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

// Route::get('/', function () {
//     if (auth()->check()) {
//         return Inertia::location(route('users.index'));
//     } else {
//         return Inertia::render('Auth/Login');
//     }
// })->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/folder-structure/{path?}', [DocumentController::class, 'getFolderStructure'])->name('document.scan.folder');
    Route::get('/test_new_folder', [DocumentController::class, 'createFolder'])->name('document.create_folder');
    Route::get('/test_view_folder', [DocumentController::class, 'folder_tree_test'])->name('document.test_view_folder');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/documment_management/{folder_id?}', [FolderController::class, 'folder_index'])->name('documment.management.folders');
    Route::post('/documment_management/store', [FolderController::class, 'folder_store'])->name('documment.management.folders.store');

    Route::get('/test_folder_download/{folder_id}', [FolderController::class, 'folder_download'])->name('folder.test.download');


});

Route::middleware('auth', 'permission:UserManager')->group(function () {
    include_once 'user_admin_route.php';
});

Route::middleware('auth', 'permission:HumanResourceManager')->group(function () {
    include_once 'human_resource_route.php';
});

Route::middleware('auth', 'permission:InventoryManager')->group(function () {
    include_once 'inventory_route.php';
});

Route::middleware('auth', 'permission:ProjectManager')->group(function () {
    include_once 'project_route.php';
});

Route::middleware('auth', 'permission:PurchasingManager')->group(function () {
    include_once 'shopping_area_route.php';
});

Route::middleware('auth', 'permission:FinanceManager')->group(function () {
    include_once 'finance_route.php';
});

require __DIR__ . '/auth.php';
