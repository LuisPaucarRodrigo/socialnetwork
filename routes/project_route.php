<?php

use App\Http\Controllers\HttpController;
use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\CalendarController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\ProjectArea\CustomersController;
use App\Http\Controllers\ProjectArea\LiquidationController;
use App\Http\Controllers\ProjectArea\PreProjectController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\TaskManagementController;
use Illuminate\Support\Facades\Route;

Route::any('/projectmanagement', [ProjectManagementController::class, 'index'])->name('projectmanagement.index');
Route::get('/projectmanagement/create', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.create');
Route::post('/projectmanagement/store', [ProjectManagementController::class, 'project_store'])->name('projectmanagement.store');
Route::post('/projectmanagement/update/{project_id}/add-employee', [ProjectManagementController::class, 'project_add_employee'])->name('projectmanagement.add.employee');
Route::delete('/projectmanagement/update/delete-employee/{pivot_id}', [ProjectManagementController::class, 'project_delete_employee'])->name('projectmanagement.delete.employee');

Route::get('/projectmanagement/resources/{project_id}', [ProjectManagementController::class, 'project_resources'])->name('projectmanagement.resources');
Route::post('/projectmanagement/resources', [ProjectManagementController::class, 'project_resources_store'])->name('projectmanagement.resources.store');
Route::post('/projectmanagement/resources/liquidate', [ProjectManagementController::class, 'project_resources_liquidate'])->name('projectmanagement.resourcesLiquidate');

Route::post('/projectmanagement/componentormaterials', [ProjectManagementController::class, 'project_componentmaterial_store'])->name('projectmanagement.componentormaterials.store');
Route::delete('/projectmanagement/componentormaterials/delete/{component_or_material_id}', [ProjectManagementController::class, 'project_componentmaterial_delete'])->name('projectmanagement.componentormaterials.delete');

//CicsaSections
Route::get('/cicsaSections', [CicsaSectionController::class, 'showSections'])->name('sections.cicsaSections');
Route::post('/cicsaSections', [CicsaSectionController::class, 'storeSection'])->name('sections.cicsaStoreSection');


//CicsaSubSections
Route::get('/cicsaSubSections', [CicsaSectionController::class, 'showSubSections'])->name('sections.cicsaSubSections');
Route::get('/cicsaSubSections/{subSection}', [CicsaSectionController::class, 'showSubSection'])->name('sections.cicsaSubSection');
Route::post('/cicsaSubSections', [CicsaSectionController::class, 'storeSubSection'])->name('sections.cicsaStoreSubSection');
Route::get('/cicsaDoTask', [CicsaSectionController::class, 'doTask'])->name('sections.cicsaTask');
Route::get('/cicsaDoTask2', [CicsaSectionController::class, 'doTask2'])->name('sections.cicsaTask2');

// Route::get('/customervisitmanagement', [CustomerVisitController::class, 'index'])->name('customervisitmanagement.index');
// Route::get('/customervisitmanagement/create', [CustomerVisitController::class, 'create'])->name('customervisitmanagement.create');
// Route::post('/customervisitmanagement/store', [CustomerVisitController::class, 'store'])->name('customervisitmanagement.store');
// Route::get('/customervisitmanagement/{id}/details', [CustomerVisitController::class, 'details'])->name('customervisitmanagement.details');
// Route::get('/customervisitmanagement/{id}/edit', [CustomerVisitController::class, 'edit'])->name('customervisitmanagement.edit');
// Route::put('/customervisitmanagement/{id}/update', [CustomerVisitController::class, 'update'])->name('customervisitmanagement.update');
// Route::delete('/customervisitmanagement/{id}/delete', [CustomerVisitController::class, 'delete'])->name('customervisitmanagement.delete');

//PreProjects
Route::any('/preprojects', [PreProjectController::class, 'index'])->name('preprojects.index');
Route::get('/preprojects/create/{preproject_id?}', [PreProjectController::class, 'create'])->name('preprojects.create');
Route::post('/preprojects/store', [PreProjectController::class, 'store'])->name('preprojects.store');
Route::get('/preprojects/{preproject}/facade', [PreProjectController::class, 'showPreprojectFacade'])->name('preprojects.facade');
Route::get('/cotizationPDF/{preproject}', [PreProjectController::class, 'getPDF'])->name('preprojects.pdf');

Route::get('/preprojects/{preproject_id}/quote', [PreProjectController::class, 'quote'])->name('preprojects.quote');
Route::get('/preprojects/load_resource_entries/{service_id}', [PreProjectController::class, 'load_resource_entries'])->name('load.resource_entries');
Route::post('/preprojects/quote_store/{quote_id?}', [PreProjectController::class, 'quote_store'])->name('preprojects.quote.store');
Route::post('/preprojects/quote/{quote_id}', [PreProjectController::class, 'acceptCotization'])->name('preprojects.accept');



Route::get('/preprojects/{preproject_id}/photoreport', [PreProjectController::class, 'photoreport_index'])->name('preprojects.photoreport.index');
Route::post('/preprojects/photoreport_store', [PreProjectController::class, 'photoreport_store'])->name('preprojects.photoreport.store');
Route::get('/preprojects/photoreport_download/{report_name}', [PreProjectController::class, 'downloadPR'])->name('preprojects.photoreport.download');
Route::get('/preprojects/photoreport_download_pdf/{pr_id}', [PreProjectController::class, 'download_pdf_PR'])->name('preprojects.photoreport_pdf.download');
Route::get('/preprojects/photoreport_show/{pr_id}', [PreProjectController::class, 'showPR'])->name('preprojects.photoreport.show');
Route::get('/preprojects/quote_pdf/{quote_id}', [PreProjectController::class, 'quote_pdf'])->name('preprojects.quote.pdf');

Route::get('/preprojects/request/{id}', [PreProjectController::class, 'request'])->name('preprojects.request.index');
Route::get('/preprojects/request/{id}/shopping', [PreProjectController::class, 'request_shopping_create'])->name('preprojects.request.create');
Route::post('/preprojects/request/store/shopping', [PreProjectController::class, 'request_shopping_store'])->name('preprojects.request.store');
Route::get('/preprojects/request/{id}/details', [PreProjectController::class, 'request_shopping_details'])->name('preprojects.request.details');
Route::get('/preprojects/request/{id}/edit', [PreProjectController::class, 'request_shopping_edit'])->name('preprojects.request.edit');
Route::put('/preprojects/request/{id}/edit', [PreProjectController::class, 'request_shopping_update'])->name('preprojects.request.update');

Route::get('/preprojects/purchase_quote/{id}', [PreProjectController::class, 'purchase_quote'])->name('preprojects.purchase_quote');
Route::get('/preprojects/purchase_quote/details/{id}', [PreProjectController::class, 'purchase_quote_details'])->name('preprojects.purchase.quote.details');


Route::get('/preprojects/{preproject_id}/providers_quotes', [PreProjectController::class, 'preproject_providersquotes_index'])->name('preprojects.providersquotes.index');
Route::post('/preprojects/providers_quotes/store', [PreProjectController::class, 'preproject_providersquotes_store'])->name('preprojects.providersquotes.store');
Route::get('/preprojects/providers_quotes/show/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_show'])->name('preprojects.providersquotes.show');
Route::get('/preprojects/providers_quotes/download/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_download'])->name('preprojects.providersquotes.download');

Route::post('/preprojects/purchase_quote/accept_decline/{purchase_quote_id}', [PreProjectController::class, "accept_decline_quote"])->name('preprojects.purchase_quote.accept_decline');


//test api
Route::get('/sunat_ruc', [HttpController::class, 'sunat_ruc'])->name('sunat');



Route::get('/preprojects/{preproject_id}/report/image', [PreProjectController::class, 'index_image'])->name('preprojects.imagereport.index');
Route::get('/preprojects/{preproject_id}/report/download_image', [PreProjectController::class, 'download_image'])->name('preprojects.imagereport.download');
Route::get('/preprojects/{image}/report/showimage', [PreProjectController::class, 'show_image'])->name('preprojects.imagereport.show');
Route::delete('/preprojects/{preproject_id}/report/delete', [PreProjectController::class, 'delete_image'])->name('preprojects.imagereport.delete');


Route::get('/projectmanagement/purchases_request/{project_id}', [ProjectManagementController::class, 'project_purchases_request_index'])->name('projectmanagement.purchases_request.index');
Route::get('/projectmanagement/purchases_request/{project_id}/create/{purchase_id?}', [ProjectManagementController::class, 'project_purchases_request_create'])->name('projectmanagement.purchases_request.create');
Route::post('/projectmanagement/purchases_request/{project_id}/store', [ProjectManagementController::class, 'project_purchases_request_store'])->name('projectmanagement.purchases_request.store');
Route::get('/projectmanagement/expenses/{project_id}', [ProjectManagementController::class, 'project_expenses'])->name('projectmanagement.expenses');
Route::get('/projectmanagement/purchases_request/details/{id}', [ProjectManagementController::class, 'project_purchases_request_details'])->name('projectmanagement.purchases_request.details');


Route::post('/projectmanagement/purchases_request/update/due_date', [ProjectManagementController::class, 'project_purchases_request_update_due_date'])->name('projectmanagement.update_due_date');
Route::post('/projectmanagement/purchases_request/update/quotedeadline', [ProjectManagementController::class, 'project_purchases_request_update_quote_deadline'])->name('projectmanagement.update_quotedeadline');
Route::get('/projectmanagement/purchases_quote/details/{purchase_quote}', [ProjectManagementController::class, 'details'])->name('projectmanagement.purchases_quote.details');



Route::post('/projectmanagement/purchases_request/update/due_date', [ProjectManagementController::class, 'project_purchases_request_update_due_date'])->name('projectmanagement.update_due_date');
Route::get('/projectmanagement/purchases_quote/details/{purchase_quote}', [ProjectManagementController::class, 'details'])->name('projectmanagement.purchases_quote.details');



//additional_cost
Route::get('/projectmanagement/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'index'])->name('projectmanagement.additionalCosts');
Route::post('/projectmanagement/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'store'])->name('projectmanagement.storeAdditionalCost');

Route::get('/projectmanagement/products/{project_id}', [ProjectManagementController::class, 'project_product_index'])->name('projectmanagement.products');
Route::post('/projectmanagement/products/{project_id}/liquidate', [LiquidationController::class, 'store'])->name('projectmanagement.productsLiquidate');
Route::get('/projectmanagement/products/{project_id}/liquidateTable', [LiquidationController::class, 'index'])->name('projectmanagement.liquidate');
Route::get('/projectmanagement/products/{project_id}/liquidateHistory', [LiquidationController::class, 'history'])->name('projectmanagement.liquidate.history');
Route::get('/projectmanagement/products/{project_id}/{project_entry}', [LiquidationController::class, 'liquidateForm'])->name('projectmanagement.liquidate.form');
Route::post('/projectmanagement/products/{project_id}/{project_entry}/liquidate', [LiquidationController::class, 'liquidate'])->name('projectmanagement.liquidate.post');



Route::get('/projectmanagement/warehouse_products/{project}/{warehouse}', [ProjectManagementController::class, 'warehouse_products'])->name('projectmanagement.warehouse_products');

Route::get('/projectmanagement/inventory_products/{inventory}', [ProjectManagementController::class, 'inventory_products'])->name('projectmanagement.inventory_products');


Route::post('/projectmanagement/products/store', [ProjectManagementController::class, 'project_product_store'])->name('projectmanagement.products.store');
Route::put('/projectmanagement/products/update/{project_product}', [ProjectManagementController::class, 'project_product_update'])->name('projectmanagement.products.update');
Route::delete('/projectmanagement/warehouse_products/{assigned}', [ProjectManagementController::class, 'warehouse_products_delete'])->name('projectmanagement.products.delete');


//Tasks Management
Route::get('/tasks/{id?}', [TaskManagementController::class, 'index'])->name('tasks.index');
Route::get('/newtask/{project_id?}', [TaskManagementController::class, 'new'])->name('tasks.new');
Route::post('/createtask', [TaskManagementController::class, 'create'])->name('tasks.create');
Route::get('/edittask/{taskId}', [TaskManagementController::class, 'edit'])->name('tasks.edit');
Route::post('/edittask/comment', [TaskManagementController::class, 'comment'])->name('tasks.edit.comment');
Route::post('/edittask/add', [TaskManagementController::class, 'add_employee'])->name('tasks.add.employee');
Route::get('/statustask/{taskId}/{status}', [TaskManagementController::class, 'status_task'])->name('tasks.edit.status');

Route::post('/tasks/duplicated', [TaskManagementController::class, 'task_duplicated'])->name('tasks.duplicated');
Route::post('/tasks/edit/date', [TaskManagementController::class, 'task_edit_date'])->name('tasks.edit.date');

//Calendar
Route::get('/calendarProjects', [CalendarController::class, 'index'])->name('projectscalendar.index');
Route::get('/calendarTasks/{project}', [CalendarController::class, 'show'])->name('projectscalendar.show');

//customers
Route::any('/customers', [CustomersController::class, 'index'])->name('customers.index');
Route::post('/customers/post', [CustomersController::class, 'store'])->name('customers.store');
Route::put('/customers/{customer}/update', [CustomersController::class, 'update'])->name('customers.update');
Route::delete('/customers/{customer}/destroy', [CustomersController::class, 'destroy'])->name('customers.destroy');
Route::get('/customers/search', [CustomersController::class, 'index'])->name('customers.search');
Route::post('/customers/search', [CustomersController::class, 'search'])->name('customers.search');

//customer_contacts
Route::get('/customers/{customer}/contacts', [CustomersController::class, 'show_contacts'])->name('customers.contacts.index');
Route::post('/customers/{customer}/contacts/post', [CustomersController::class, 'store_contact'])->name('customers.contacts.store');
Route::put('/customers/{customer}/contacts/{customer_contact}/update', [CustomersController::class, 'update_contact'])->name('customers.contacts.update');
Route::delete('/customers/{customer}/contacts/{customer_contact}/destroy', [CustomersController::class, 'destroy_contact'])->name('customers.contacts.destroy');
