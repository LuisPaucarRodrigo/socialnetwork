<?php

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

// Route::get('/', function () {
//     return Inertia::render('Auth/Login');
// });

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
});

Route::middleware(['auth', 'permission:UserManager','checkPlatformWeb'])->group(function () {
    include_once 'user_admin_route.php';
});

Route::middleware(['auth', 'permission:HumanResourceManager', 'checkPlatformWeb'])->group(function () {
    include_once 'human_resource_route.php';
});

Route::middleware(['auth', 'permission:InventoryManager', 'checkPlatformWeb'])->group(function () {
    include_once 'inventory_route.php';
});

Route::middleware(['auth', 'permission:ProjectManager', 'checkPlatformWeb'])->group(function () {
    include_once 'project_route.php';
});

Route::middleware(['auth', 'permission:PurchasingManager', 'checkPlatformWeb'])->group(function () {
    include_once 'shopping_area_route.php';
});

Route::middleware(['auth', 'permission:FinanceManager', 'checkPlatformWeb'])->group(function () {
    include_once 'finance_route.php';
});

Route::middleware(['auth', 'permission:DocumentGestion', 'checkPlatformWeb'])->group(function () {
    include_once 'documentgestion_route.php';
});

Route::middleware(['auth', 'permission:SocialNetwork', 'checkPlatformWeb'])->group(function () {
    include_once 'snsot_route.php';
});

require __DIR__ . '/auth.php';
