<?php

use App\Http\Controllers\SocialNetwork\SotController;
use Illuminate\Support\Facades\Route;

Route::get('/social-network/sot', [SotController::class, 'sot_index'])->name('socialnetwork.sot');
Route::delete('/social-network/sot-delete/{sot_id}', [SotController::class, 'sot_delete'])->name('socialnetwork.sot.delete');


Route::middleware('permission:SocialNetworkProgramation')->group(function () {
    Route::get('/social-network/sot-programation', [SotController::class, 'sot_programation'])->name('socialnetwork.sot.programation');
    Route::post('/social-network/sot-programation-store', [SotController::class, 'sot_programation_store'])->name('socialnetwork.sot.programation.store');
    Route::post('/social-network/sot-programation-update/{sot_id}', [SotController::class, 'sot_programation_update'])->name('socialnetwork.sot.programation.update');

});