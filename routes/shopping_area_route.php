<?php

use App\Http\Controllers\ShoppingArea\PaymentController;
use App\Http\Controllers\ShoppingArea\ProviderController;
use App\Http\Controllers\ShoppingArea\PurchaseOrdersController;
use App\Http\Controllers\ShoppingArea\PurchaseRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/shopping_area/purchasesrequest', [PurchaseRequestController::class, 'index'])->name('purchasesrequest.index');
Route::get('/shopping_area/purchasesrequest/search/{request}', [PurchaseRequestController::class, 'search'])->name('purchasesrequest.search');
Route::get('/shopping_area/purchasesrequest/create_request/{project_id?}', [PurchaseRequestController::class, 'create'])->name('purchasesrequest.create');
Route::post('/shopping_area/purchasesrequest/store_request', [PurchaseRequestController::class, 'store'])->name('purchasesrequest.store');
Route::get('/shopping_area/purchasesrequest/edit/{id}/{project_id?}', [PurchaseRequestController::class, 'edit'])->name('purchasesrequest.edit');
Route::put('/shopping_area/purchasesrequest/update/{id}', [PurchaseRequestController::class, 'update'])->name('purchasesrequest.update');
Route::get('/shopping_area/purchasesrequest/quotes/{id}', [PurchaseRequestController::class, 'index_quotes'])->name('purchasesrequest.quotes');
Route::get('/shopping_area/purchasesrequest/deadline_complete/quotes/{id}', [PurchaseRequestController::class, 'purchase_quote_deadline_complete'])->name('purchasesrequest.quote_deadline.complete');
Route::get('/shopping_area/purchasesrequest/quotes/{id}/preview', [PurchaseRequestController::class, 'showDocument'])->name('purchasesrequest.show');
Route::get('/shopping_area/purchasesrequest/details/{id}', [PurchaseRequestController::class, 'details'])->name('purchasingrequest.details');
Route::get('/shopping_area/purchasesrequest/export/{id}', [PurchaseRequestController::class, 'export'])->name('purchasingrequest.export');
Route::post('/shopping_area/purchasesrequest/orders', [PurchaseRequestController::class, 'quote'])->name('purchasesrequest.storequotes');
Route::post('/shopping_area/purchasesrequest/reject/{id}', [PurchaseRequestController::class, 'reject_request'])->name('purchasesrequest.reject_request');
Route::get('/shopping_area/purchasesrequest/doTask', [PurchaseRequestController::class, 'doTask'])->name('purchasesrequest.task');

Route::get('/shopping_area/purchaseorders', [PurchaseOrdersController::class, 'index'])->name('purchaseorders.index');
Route::get('/shopping_area/purchaseorders/search/{request}/{history}', [PurchaseOrdersController::class, 'search'])->name('purchaseorders.search');
Route::get('/shopping_area/purchaseorders_details/{purchase_order_id}', [PurchaseOrdersController::class, 'purchase_order_view'])->name('purchaseorders.details');
Route::get('/shopping_area/purchaseorders/history', [PurchaseOrdersController::class, 'history'])->name('purchaseorders.history');
Route::post('/shopping_area/purchaseorders/state', [PurchaseOrdersController::class, 'state'])->name('purchaseorders.state');
Route::get('/shopping_area/alarms', [PurchaseOrdersController::class, 'purchase_orders_alarms'])->name('purchaseorders.alarms');
Route::get('/shopping_area/purchaseorders/{id}/export', [PurchaseOrdersController::class, 'purchase_orders_export'])->name('purchaseorders.export.order');
Route::get('/shopping_area/purchasesorder/{id}/preview', [PurchaseOrdersController::class, 'showFacture'])->name('purchasesorder.showFacture');

Route::get('/shopping_area/purchasesrequest/purchase_quote/details/{id}', [PurchaseRequestController::class, 'purchase_quote_complete_details'])->name('purchase.quote.details.complete');

Route::get('/shopping_area/providers', [ProviderController::class, 'index'])->name('providersmanagement.index');
Route::get('/shopping_area/providers/search/{request}', [ProviderController::class, 'search'])->name('providersmanagement.search');
Route::get('/shopping_area/providers/create', [ProviderController::class, 'create'])->name('providersmanagement.create');
Route::post('/shopping_area/providers/store', [ProviderController::class, 'store'])->name('providersmanagement.store');
Route::get('/shopping_area/providers/edit/{id}', [ProviderController::class, 'edit'])->name('providersmanagement.edit');
Route::put('/shopping_area/providers/update/{id}', [ProviderController::class, 'update'])->name('providersmanagement.update');
Route::delete('/shopping_area/providers/destroy/{id}', [ProviderController::class, 'destroy'])->name('providersmanagement.destroy');

Route::post('/shopping_area/providers/category', [ProviderController::class, 'category_provider'])->name('provider.category');
Route::post('/shopping_area/providers/segment', [ProviderController::class, 'segment_provider'])->name('provider.segment');

Route::get('/shopping_area/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/shopping_area/payment/search/{request}', [PaymentController::class, 'search'])->name('payment.search');
Route::post('/shopping_area/payment/pay', [PaymentController::class, 'payment_pay'])->name('payment.pay');

Route::get('/shopping_area/alarm/payment', [PaymentController::class, 'alarm_payments'])->name('payment.alarm');

// Route::post('/shopping_area/existing/products',[PurchaseRequestController::class,'existing_products'])->name('existing.products');