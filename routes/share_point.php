<?php

use App\Http\Controllers\Services\SharePointController;
use Illuminate\Support\Facades\Route;

Route::get('/sharepoint/index_cost_line', [SharePointController::class, 'index'])->name('sharepoint.index');
