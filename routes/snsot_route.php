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

Route::middleware('permission:SocialNetworkOperation')->group(function () {
    Route::get('/social-network/sot-operation', [SotController::class, 'sot_operation'])->name('socialnetwork.sot.operation');
    Route::post('/social-network/sot-operation-store', [SotController::class, 'sot_operation_store'])->name('socialnetwork.sot.operation.store');
    Route::put('/social-network/sot-operation-update/{sot_operation_id}', [SotController::class, 'sot_operation_update'])->name('socialnetwork.sot.operation.update');
});

Route::middleware('permission:SocialNetworkLiquidation')->group(function () {
    Route::get('/social-network/sot-liquidation', [SotController::class, 'sot_liquidation'])->name('socialnetwork.sot.liquidation');
    Route::post('/social-network/sot-liquidation-store', [SotController::class, 'sot_liquidation_store'])->name('socialnetwork.sot.liquidation.store');
    Route::put('/social-network/sot-programation-update/{sot_liquidation_id}', [SotController::class, 'sot_liquidation_update'])->name('socialnetwork.sot.liquidation.update');
});

Route::middleware('permission:SocialNetworkCharge')->group(function () {
    Route::get('/social_network/paymentArea/index', [SotController::class, 'sot_payment_index'])->name('sn.paymentArea.index');
    Route::put('/social_network/paymentArea/{sot_id}/update', [SotController::class, 'sot_payment_udpate'])->name('sn.paymentArea.update');
});

Route::middleware('permission:SocialNetworkControl')->group(function () {
    Route::get('/social_network/controlArea/index', [SotController::class, 'sot_control_index'])->name('sn.controlArea.index');
    Route::put('/social_network/controlArea/{sot_id}/update', [SotController::class, 'sot_control_udpate'])->name('sn.controlArea.update');
});
