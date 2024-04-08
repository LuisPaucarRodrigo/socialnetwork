<?php

use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\PurchaseProductsController;
use App\Http\Controllers\Inventory\ResourceManagementController;
use App\Http\Controllers\Inventory\SpecialWarehouseController;
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
Route::get('/inventory/warehouses/{warehouse}/header', [WarehousesController::class, 'showWarehouseHeader'])->name('warehouses.warehouseHeader');
Route::post('/inventory/warehouses/{warehouse}/header', [WarehousesController::class, 'storeWarehouseHeader'])->name('warehouses.storeWarehouseHeader');
Route::post('/inventory/warehouses', [WarehousesController::class, 'storeWarehouse'])->name('warehouses.storeWarehouse');
Route::delete('/inventory/warehouses/{warehouse}/destroy', [WarehousesController::class, 'destroyWarehouse'])->name('warehouses.destroyWarehouse');

//products
Route::get('/inventory/warehouses/{warehouse}/product', [ProductController::class, 'index'])->name('warehouses.products');
Route::get('/inventory/warehouses/{warehouse}/product/create', [ProductController::class, 'create'])->name('warehouses.createProduct');
Route::get('/inventory/warehouses/{warehouse}/product/{product}/show', [ProductController::class, 'show'])->name('warehouses.showProduct');
Route::post('/inventory/warehouses/{warehouse}/product/create', [ProductController::class, 'store'])->name('warehouses.storeProduct');
Route::delete('/inventory/warehouses/{warehouse}/product/{product}/destroy', [ProductController::class, 'destroy'])->name('warehouses.destroyProduct');
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




//Special Warehouses
Route::get('/inventory/{warehouse_id}/special_products/', [SpecialWarehouseController::class, 'special_products_index'])->name('inventory.special_products.index');
Route::get('/inventory/{warehouse_id}/special_products/create/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_create'])->name('inventory.special_products.create');
Route::post('/inventory/special_products/store/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_store'])->name('inventory.special_products.store');
Route::delete('/inventory/special_products/destroy/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_destroy'])->name('inventory.special_products.destroy');


Route::get('/inventory/{warehouse_id}/special_dispatch_index/', [SpecialWarehouseController::class, 'special_dispatch_index'])->name('inventory.special_dispatch.index');
Route::get('/inventory/{warehouse_id}/special_dispatch_historial/', [SpecialWarehouseController::class, 'special_dispatch_historial'])->name('inventory.special_dispatch.historial');
Route::post('/inventory/special_dispatch_accept_decline/{project_entry_id}', [SpecialWarehouseController::class, 'special_dispatch_accept_decline'])->name('inventory.special_dispatch.accept_decline');
Route::post('/inventory/special_dispatch_output_store', [SpecialWarehouseController::class, 'special_dispatch_output_store'])->name('inventory.special_dispatch_output.store');