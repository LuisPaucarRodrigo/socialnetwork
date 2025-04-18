<?php

use App\Constants\RolesConstants;
use App\Http\Controllers\DocumentManagement\FolderController;
use App\Http\Controllers\ProjectArea\StaticCostsController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ManagementRolsController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\PreProjectController;
use App\Http\Controllers\ShoppingArea\PurchaseRequestController;
use App\Http\Controllers\HumanResource\FormationDevelopment;
use App\Http\Controllers\ProjectArea\TaskManagementController;
use App\Http\Controllers\HumanResource\VacationController;
use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\Inventory\PurchaseProductsController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\Inventory\SpecialWarehouseController;
use App\Http\Controllers\Inventory\WarehousesController;
use App\Http\Controllers\ShoppingArea\ProviderController;
use Illuminate\Support\Facades\Route;
use App\Enums\Permissions\UserRolePermissions;


Route::middleware(['auth', 'checkPlatformWeb'])->group(function () { 

// USERS
Route::get('users/linkEmployee/{user}', [UserController::class, 'linkEmployee'])
    ->middleware('permission:' . UserRolePermissions::USER_LINK_EMPLOYEE->value)
    ->name('users.linkEmployee');

Route::get('users/edit/{id}', [UserController::class, 'edit'])
    ->middleware('permission:' . UserRolePermissions::USER_EDIT->value)
    ->name('users.edit');

Route::put('users/update/{id}', [UserController::class, 'update'])
    ->middleware('permission:' . UserRolePermissions::USER_UPDATE->value)
    ->name('users.update');

Route::post('users/delete/{id}', [UserController::class, 'delete'])
    ->middleware('permission:' . UserRolePermissions::USER_DELETE->value)
    ->name('users.destroy');

Route::get('users', [UserController::class, 'index_user'])
    ->middleware('permission:' . UserRolePermissions::USERS_INDEX->value)
    ->name('users.index');

Route::post('users/search', [UserController::class, 'search'])
    ->middleware('permission:' . UserRolePermissions::USERS_SEARCH->value)
    ->name('users.search');

Route::get('users/details/{id}', [UserController::class, 'details'])
    ->middleware('permission:' . UserRolePermissions::USERS_DETAILS->value)
    ->name('users.details');


// ROLS
Route::get('rols', [ManagementRolsController::class, 'rols_index'])
    ->middleware('permission:' . UserRolePermissions::ROL_VIEW->value)
    ->name('rols.index');

Route::post('rols/store', [ManagementRolsController::class, 'store'])
    ->middleware('permission:' . UserRolePermissions::ROL_CREATE->value)
    ->name('rols.store');

Route::post('rols/update/{id}', [ManagementRolsController::class, 'update'])
    ->middleware('permission:' . UserRolePermissions::ROL_UPDATE->value)
    ->name('rols.update');

Route::delete('rols/delete/{id}', [ManagementRolsController::class, 'delete'])
    ->middleware('permission:' . UserRolePermissions::ROL_DELETE->value)
    ->name('rols.delete');

Route::get('rols/details/{id}', [ManagementRolsController::class, 'details'])
    ->middleware('permission:' . UserRolePermissions::ROL_DETAILS->value)
    ->name('rols.details');


});


