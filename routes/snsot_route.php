<?php

use App\Http\Controllers\SocialNetwork\SotController;
use Illuminate\Support\Facades\Route;

Route::get('/social-network/sot', [SotController::class, 'sot_index'])->name('socialnetwork.sot');

