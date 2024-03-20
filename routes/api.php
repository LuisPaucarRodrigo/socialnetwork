<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users', [ApiController::class, 'users']);
    Route::get('/preproject', [ApiController::class, 'preproject']);
    Route::get('/preprojectespecific/{id}', [ApiController::class, 'preprojectespecific']);
    Route::post('/preprojectimage', [ApiController::class, 'preprojectimage']);
    Route::post('/logout', [ApiController::class, 'logout']);
});

