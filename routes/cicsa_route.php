<?php

use App\Http\Controllers\Cicsa\CicsaController;
use Illuminate\Support\Facades\Route;


Route::get('/cicsa_process', [CicsaController::class, 'index'])->name('cicsa.index');