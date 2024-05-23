<?php

use App\Http\Controllers\SocialNetwork\SotController;
use Illuminate\Support\Facades\Route;

Route::get('/social-network/sot', [SotController::class, 'sot_index'])->name('socialnetwork.sot');


Route::middleware('permission:SocialNetworkProgramation')->group(function () {
    Route::get('/social-network/sot-programation', [SotController::class, 'sot_programation'])->name('socialnetwork.sot.programation');
});