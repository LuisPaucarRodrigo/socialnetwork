<?php

use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\PurchaseProductsController;
use App\Http\Controllers\Inventory\ResourceManagementController;
use App\Http\Controllers\Inventory\WarehousesController;
use Illuminate\Support\Facades\Route;

//Resources
Route::get('/resources', [ResourceManagementController::class, 'index'])->name('resources.index');
Route::get('/resources/new', [ResourceManagementController::class, 'new'])->name('resources.new');
Route::get('/resources/details/{id}', [ResourceManagementController::class, 'details'])->name('resources.details');
Route::post('/resources/create', [ResourceManagementController::class, 'create'])->name('resource.create');
Route::get('/resources/edit/{resourceId}', [ResourceManagementController::class, 'edit'])->name('resources.edit');
Route::put('/resources/edit/{resourceId}', [ResourceManagementController::class, 'update'])->name('resource.update');
Route::delete('/resources/delete/{resourceId}', [ResourceManagementController::class, 'destroy'])->name('resource.delete');

Route::post('resource_description/store', [ResourceManagementController::class, 'resource_description_store'])->name('resource_description.store');
//warehouses
Route::get('/inventory/warehouses', [WarehousesController::class, 'showWarehouses'])->name('warehouses.warehouses');
Route::get('/inventory/warehouses/{warehouse}', [WarehousesController::class, 'showWarehouse'])->name('warehouses.warehouse');

//products
Route::get('/inventory/products/{warehouse}', [WarehousesController::class, 'showProducts'])->name('warehouses.products');
Route::get('/inventory/products/{warehouse}/{inventory}/get', [WarehousesController::class, 'showEntries'])->name('warehouses.products.entries');
Route::get('/inventory/products/{warehouse}/create', [WarehousesController::class, 'createProducts'])->name('warehouses.createNormalProduct');
Route::post('/inventory/products/{warehouse}/store', [WarehousesController::class, 'storeProducts'])->name('warehouses.storeNormalProduct');

//approvePurchaseOrders
Route::get('/inventory/approve_purchase_orders/{warehouse}', [WarehousesController::class, 'approvePurchaseOrders'])->name('warehouses.purchaseorders.approve');
Route::post('/inventory/approve_purchase_orders/{warehouse}/approve', [WarehousesController::class, 'approve'])->name('warehouses.purchaseorders.approve.post');

//dispatches
Route::get('/inventory/dispatches/{warehouse}', [WarehousesController::class, 'showDispatches'])->name('warehouses.dispatches');
Route::post('/inventory/dispatches/{warehouse}/accept_or_decline', [WarehousesController::class, 'acceptOrDeclineDispatch'])->name('warehouses.dispatches.acceptordecline');

//outputs
Route::get('/inventory/warehouses/{warehouse}/outputs', [ProductController::class, 'outputs_index'])->name('warehouses.outputs');
Route::post('/inventory/warehouses//outputs/store', [ProductController::class, 'outputs_store'])->name('projectmanagement.outputs.store');
Route::get('/inventory/warehouses/{warehouse}/outputs_history', [ProductController::class, 'outputs_history_index'])->name('warehouses.outputs_history');
Route::delete('/inventory/warehouses/output_delete/{output}', [ProductController::class, 'output_delete'])->name('warehouses.output_delete');

//purchase_products
Route::get('/inventory/purchase_products/products', [PurchaseProductsController::class, 'index'])->name('inventory.purchaseproducts');
Route::get('/inventory/purchase_products/products/search/{request}', [PurchaseProductsController::class, 'search'])->name('inventory.purchaseproducts.search');
Route::post('/inventory/purchase_products/products/post', [PurchaseProductsController::class, 'store'])->name('inventory.purchaseproducts.store');
Route::put('/inventory/purchase_products/products/{purchase_product}/update', [PurchaseProductsController::class, 'update'])->name('inventory.purchaseproducts.update');
Route::put('/inventory/purchase_products/products/{purchase_product}/disable', [PurchaseProductsController::class, 'disable'])->name('inventory.purchaseproducts.disable');