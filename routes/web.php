<?php

use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentGestion\ArchivesController;
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

Route::get('/documentGestion/{folder}/archives', [ArchivesController::class, 'show'])->name('archives.show');
Route::post('/documentGestion/{folder}/archives/post', [ArchivesController::class, 'create'])->name('archives.post');
Route::get('/documentGestion/{folder}/archives/{archive}/download', [ArchivesController::class, 'downloadArchive'])->name('archives.download');
Route::delete('/documentGestion/{folder}/archives/{archive}/destroy', [ArchivesController::class, 'destroy'])->name('archives.destroy');
Route::post('/documentGestion/{folder}/archives/{archive}/assignUsers', [ArchivesController::class, 'assignUsers'])->name('archives.assign.users');
Route::get('/documentGestion/{folder}/archives/{archive}/observations', [ArchivesController::class, 'observationsPerArchive'])->name('archives.observations');
Route::post('/documentGestion/archives/{archive}/observate', [ArchivesController::class, 'saveObservation'])->name('archives.observations.save');
Route::post('/documentGestion/archives/{archive}/upgrade', [ArchivesController::class, 'upgradeArchive'])->name('archives.upgrade');

require __DIR__ . '/auth.php';
