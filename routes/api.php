<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VacationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [ApiController::class, 'login']);
Route::get('/preproject', [ApiController::class, 'preproject']);
Route::get('/preprojectespecific/{id}', [ApiController::class, 'preprojectespecific']);
Route::post('/preprojectimage', [ApiController::class, 'preprojectimage']);
Route::middleware('auth:sanctum')->group(function (){
    // Route::get('/preproject', [ApiController::class, 'preproject']);
});

// Route::get('/vacation', [VacationController::class, 'index']);
// Route::get('/vacation/{vacation}', [VacationController::class, 'show']);
// Route::post('/vacation', [VacationController::class, 'store']);
// Route::put('/vacation/{vacation}', [VacationController::class, 'update']);
// Route::delete('/vacation/{vacation}', [VacationController::class, 'destroy']);



