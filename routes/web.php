<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\ProjectScheduleController;
use App\Http\Controllers\ProjectArea\ProjectReportsController;
use App\Http\Controllers\ShoppingArea\PurchaseRequestController;
use App\Http\Controllers\ShoppingArea\ProviderController;
use App\Http\Controllers\ShoppingArea\PurchaseOrdersController;
use App\Http\Controllers\ShoppingArea\PurchaseReportsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/information_additional', fn () => Inertia::render('InformacionAdicional'))->name('information_additional');
    Route::get('/management_employees', [ManagementEmployees::class, 'index'])->name('management.employees');
    Route::get('/management_employees/information_additional', [ManagementEmployees::class, 'index_info_additional'])->name('management.employees.information');
    Route::post('/management_employees/information_additional/create', [ManagementEmployees::class, 'create'])->name('management.employees.information.create');
    Route::get('/management_employees/information_additional/details/{id}', [ManagementEmployees::class, 'details'])->name('management.employees.information.details');
    Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])->name('management.employees.destroy');
    Route::get('/management_employees/information_additional/details/download/{filename}', [ManagementEmployees::class, 'download'])->name('management.employees.information.details.download');
    Route::get('/spreadsheets', [SpreadsheetsController::class, 'index'])->name('spreadsheets.index');







    Route::get('/projectmanagement', [ProjectManagementController::class, 'index'])->name('projectmanagement.index');

    Route::get('/projectschedule', [ProjectScheduleController::class, 'index'])->name('projectschedule.index');

    Route::get('/projectreports', [ProjectReportsController::class, 'index'])->name('projectreports.index');



    
    Route::get('/purchasesrequest', [PurchaseRequestController::class, 'index'])->name('purchasesrequest.index');
    Route::get('/purchasesrequest/create_request', [PurchaseRequestController::class, 'create'])->name('purchasesrequest.create');
    Route::post('/purchasesrequest/store_request', [PurchaseRequestController::class, 'store'])->name('purchasesrequest.store');
    Route::get('/purchasesrequest/quotes/{id}', [PurchaseRequestController::class, 'index_quotes'])->name('purchasesrequest.quotes');
    
    Route::get('/purchaseorders', [PurchaseOrdersController::class, 'index'])->name('purchaseorders.index');

    Route::get('/purchasereports', [PurchaseReportsController::class, 'index'])->name('purchasereports.index');

    Route::get('/providers', [ProviderController::class, 'index'])->name('providersmanagement.index');
    Route::get('/providers/information_additional', [ProviderController::class, 'create'])->name('providersmanagement.information');
    Route::post('/providers/information_additional/store', [ProviderController::class, 'store'])->name('providersmanagement.information.store');

    Route::get('users', [UserController::class, 'index_user'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
