<?php

use App\Http\Controllers\SocialNetwork\SotController;
use Illuminate\Support\Facades\Route;

Route::get('/social-network/sot', [SotController::class, 'sot_index'])->name('socialnetwork.sot');

//SOT CONTROL AREA
Route::put('/social_network/controlArea/index', [SotController::class,'sot_control_index'])->name('sn.controlArea.index');
Route::put('/social_network/controlArea/{sot_id}/update', [SotController::class,'sot_control_udpate'])->name('sn.controlArea.update');

