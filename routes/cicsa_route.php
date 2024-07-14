<?php

use App\Http\Controllers\Cicsa\CicsaController;
use Illuminate\Support\Facades\Route;


Route::get('/cicsa_process', [CicsaController::class, 'index'])->name('cicsa.index');

Route::get('/cicsa_assignation', [CicsaController::class, 'indexAssignation'])->name('assignation.index');
Route::put('/cicsa_assignation/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreAssignation'])->name('assignation.storeOrUpdate');

Route::get('/cicsa_feasibilities', [CicsaController::class, 'indexFeasibilities'])->name('feasibilities.index');
Route::put('/cicsa_feasibilities/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreFeasibilities'])->name('feasibilities.storeOrUpdate');


Route::get('/cicsa_material', [CicsaController::class, 'indexMaterial'])->name('material.index');
Route::put('/cicsa_material/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreMaterial'])->name('material.storeOrUpdate');

Route::get('/cicsa_purchase_order', [CicsaController::class, 'indexPurchaseOrder'])->name('purchase.order.index');
Route::put('/cicsa_purchase_order/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStorePurchaseOrder'])->name('purchaseOrder.storeOrUpdate');





Route::get('/cicsa_installation', [CicsaController::class, 'indexInstallation'])->name('cicsa.installation.index');
Route::post('/cicsa_installation/store/{ci_id?}', [CicsaController::class, 'updateOrStoreInstallation'])->name('cicsa.installation.store');
