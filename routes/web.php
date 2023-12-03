<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VacationController;

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
    Route::get('/management_employees',[ManagementEmployees::class, 'index'])->name('management.employees');
    Route::get('/management_employees/information_additional',[ManagementEmployees::class, 'index_info_additional'])->name('management.employees.information');
    Route::post('/management_employees/information_additional/create',[ManagementEmployees::class, 'create'])->name('management.employees.information.create');
    Route::get('/management_employees/information_additional/details/{id}',[ManagementEmployees::class, 'details'])->name('management.employees.information.details');

    Route::get('users', [UserController::class, 'index_user'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Vacation
    Route::get('/management_vacation',[VacationController::class, 'index'])->name('management.vacation');
    Route::get('/management_vacation/information_additional',[VacationController::class, 'index_info_additional'])->name('management.vacation.information');
    Route::get('/management_vacation/information_additional/{vacation}', [VacationController::class, 'edit_info_additional'])->name('management.vacation.information.edit');
    Route::post('/management_vacation/information_additional/create',[VacationController::class, 'create'])->name('management.vacation.information.create');
    Route::put('/management_vacation/information_additional/{vacation}/update',[VacationController::class, 'update'])->name('management.vacation.information.update');
    Route::delete('/management_vacation/information_additional/{vacation}/delete',[VacationController::class, 'destroy'])->name('management.vacation.information.destroy');
});

require __DIR__.'/auth.php';
