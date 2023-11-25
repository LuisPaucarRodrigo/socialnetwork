<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\FormationDevelopment;
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
    Route::get('/management_employees',[ManagementEmployees::class, 'index'])->name('management.employees');
    Route::get('/management_employees/information_additional',[ManagementEmployees::class, 'index_info_additional'])->name('management.employees.information');
    Route::post('/management_employees/information_additional/create',[ManagementEmployees::class, 'create'])->name('management.employees.information.create');
    Route::get('/management_employees/information_additional/details/{id}',[ManagementEmployees::class, 'details'])->name('management.employees.information.details');
    Route::get('/management_employees/information_additional/details/download/{filename}',[ManagementEmployees::class,'download'])->name('management.employees.information.details.download');
    
    
    //Formation Development program
    Route::get('/management_employees/formation_development',[FormationDevelopment::class, 'index'])->name('management.employees.formation_development');


    //Formation Programas
    Route::get('/management_employees/formation_development/formation_programs',[FormationDevelopment::class, 'formation_programs_index'])->name('management.employees.formation_development.formation_programs');
    Route::get('/management_employees/formation_development/formation_programs/create',[FormationDevelopment::class, 'formation_programs_create'])->name('management.employees.formation_development.formation_programs.create');
    Route::post('/management_employees/formation_development/formation_programs/store',[FormationDevelopment::class, 'formation_programs_store'])->name('management.employees.formation_development.formation_programs.store');

    //Trainings
    Route::get('/management_employees/formation_development/trainings',[FormationDevelopment::class, 'trainings_index'])->name('management.employees.formation_development.trainings');
    Route::get('/management_employees/formation_development/trainings/create',[FormationDevelopment::class, 'trainings_create'])->name('management.employees.formation_development.trainings.create');
    Route::post('/management_employees/formation_development/trainings/store',[FormationDevelopment::class, 'trainings_store'])->name('management.employees.formation_development.trainings.store');
    

    Route::get('users', [UserController::class, 'index_user'])->name('users.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
