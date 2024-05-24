<?php

use App\Http\Controllers\SocialNetwork\SotController;
use Illuminate\Support\Facades\Route;

Route::get('/social-network/sot', [SotController::class, 'sot_index'])->name('socialnetwork.sot');
//SOT PAYMENT AREA
Route::get('/social_network/paymentArea/index', [SotController::class,'sot_payment_index'])->name('sn.paymentArea.index');
Route::put('/social_network/paymentArea/{sot_id}/update', [SotController::class,'sot_payment_udpate'])->name('sn.paymentArea.update');
//SOT CONTROL AREA
Route::get('/social_network/controlArea/index', [SotController::class,'sot_control_index'])->name('sn.controlArea.index');
Route::put('/social_network/controlArea/{sot_id}/update', [SotController::class,'sot_control_udpate'])->name('sn.controlArea.update');

