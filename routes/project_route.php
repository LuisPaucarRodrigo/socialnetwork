<?php

use App\Http\Controllers\HttpController;
use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\CalendarController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\ProjectArea\CustomersController;
use App\Http\Controllers\ProjectArea\LiquidationController;
use App\Http\Controllers\ProjectArea\PreProjectController;
use App\Http\Controllers\ProjectArea\ProjectDocumentController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\ProjectPintController;
use App\Http\Controllers\ProjectArea\StaticCostsController;
use App\Http\Controllers\ProjectArea\TaskManagementController;
use App\Http\Controllers\ProjectArea\ServicesLiquidationsController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:ProjectManager')->group(function () {
    //Customers
    Route::post('/customers/post', [CustomersController::class, 'store'])->name('customers.store');
    Route::put('/customers/{customer}/update', [CustomersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}/destroy', [CustomersController::class, 'destroy'])->name('customers.destroy');

    //Customers contacts
    Route::get('/customers/{customer}/contacts', [CustomersController::class, 'show_contacts'])->name('customers.contacts.index');
    Route::post('/customers/{customer}/contacts/post', [CustomersController::class, 'store_contact'])->name('customers.contacts.store');
    Route::put('/customers/{customer}/contacts/{customer_contact}/update', [CustomersController::class, 'update_contact'])->name('customers.contacts.update');
    Route::delete('/customers/{customer}/contacts/{customer_contact}/destroy', [CustomersController::class, 'destroy_contact'])->name('customers.contacts.destroy');

    //Preproject
    Route::get('/preprojects/create/{preproject_id?}', [PreProjectController::class, 'create'])->name('preprojects.create');
    Route::post('/preprojects/store', [PreProjectController::class, 'store'])->name('preprojects.store');

    //Assign users
    Route::post('/preprojects/assignUser', [PreProjectController::class, 'preproject_users'])->name('preprojects.assign.users');

    //Preproject images
    Route::put('/preprojects/{preproject_image_id}/report/image', [PreProjectController::class, 'approve_reject_image'])->name('preprojects.imagereport.approveReject');
    Route::get('/preprojects/{preproject_code_id}/codereport', [PreProjectController::class, 'approve_code'])->name('preprojects.codereport.approveCode');
    Route::delete('/preprojects/{preproject_id}/report/delete', [PreProjectController::class, 'delete_image'])->name('preprojects.imagereport.delete');

    //Photographic report
    Route::post('/preprojects/photoreport_store', [PreProjectController::class, 'photoreport_store'])->name('preprojects.photoreport.store');

    //Preproject products
    Route::post('/preprojects/products/post', [PreProjectController::class, 'preproject_product_store'])->name('preprojects.products.store');
    Route::get('/preprojects/products/{warehouse_id}/getWarehouse', [PreProjectController::class, 'preproject_warehouse_products'])->name('preprojects.warehouse_products');
    Route::get('/preprojects/products/{inventory_id}/getInventory', [PreProjectController::class, 'preproject_inventory_products'])->name('preprojects.inventory_products');

    //Preproject purchase request
    Route::get('/preprojects/request/{id}/shopping', [PreProjectController::class, 'request_shopping_create'])->name('preprojects.request.create');
    Route::post('/preprojects/request/store/shopping', [PreProjectController::class, 'request_shopping_store'])->name('preprojects.request.store');
    Route::get('/preprojects/request/{id}/edit', [PreProjectController::class, 'request_shopping_edit'])->name('preprojects.request.edit');
    Route::put('/preprojects/request/{id}/update', [PreProjectController::class, 'request_shopping_update'])->name('preprojects.request.update');
    Route::post('/preprojects/purchase_quote/accept_decline/{purchase_quote_id}', [PreProjectController::class, "accept_decline_quote"])->name('preprojects.purchase_quote.accept_decline');

    //Preproject quote
    Route::get('/preprojects/{preproject_id}/quote', [PreProjectController::class, 'quote'])->name('preprojects.quote');
    Route::post('/preprojects/quote/oficially/rejected', [PreProjectController::class, 'preproject_quote_rejected'])->name('preproject_quote.rejected');
    Route::post('/preprojects/quote/oficially/canceled', [PreProjectController::class, 'preproject_quote_canceled'])->name('preproject_quote.canceled');
    Route::get('/cotizationPDF/{preproject}', [PreProjectController::class, 'getPDF'])->name('preprojects.pdf');
    Route::post('/preprojects/quote/{quote_id}', [PreProjectController::class, 'acceptCotization'])->name('preprojects.accept');
    Route::post('/preprojects/quote_store/{quote_id?}', [PreProjectController::class, 'quote_store'])->name('preprojects.quote.store');


    Route::get('/preprojects/load_resource_entries/{service_id}', [PreProjectController::class, 'load_resource_entries'])->name('load.resource_entries');


    //Project
    Route::get('/project/create', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.create');
    Route::post('/project/store', [ProjectManagementController::class, 'project_store'])->name('projectmanagement.store');
    Route::get('/projectmanagement/update/{project_id}', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.update');
    Route::post('/project/update/{project_id}/add-employee', [ProjectManagementController::class, 'project_add_employee'])->name('projectmanagement.add.employee');
    Route::post('/project/liquidation', [ProjectManagementController::class, 'liquidate_project'])->name('projectmanagement.liquidation');


    //Project tasks
    Route::get('/project/task/create/{project_id?}', [TaskManagementController::class, 'create'])->name('tasks.create');
    Route::post('/projecmanagement/task/store', [TaskManagementController::class, 'store'])->name('tasks.store');
    Route::post('/projecmanagement/task/add/comment', [TaskManagementController::class, 'comment'])->name('tasks.add.comment');
    Route::post('/projecmanagement/task/add/employee', [TaskManagementController::class, 'add_employee'])->name('tasks.add.employee');
    Route::get('/statustask/{taskId}/{status}', [TaskManagementController::class, 'status_task'])->name('tasks.edit.status');
    Route::post('/tasks/duplicated', [TaskManagementController::class, 'task_duplicated'])->name('tasks.duplicated');
    Route::post('/tasks/edit/date', [TaskManagementController::class, 'task_edit_date'])->name('tasks.edit.date');

    //Project resources
    Route::post('/project/resources_liquidation', [ServicesLiquidationsController::class, 'store'])->name('projectmanagement.resources.liquidate');

    //Project purchase request
    Route::get('/project/purchases_request/{project_id}/create/{purchase_id?}', [ProjectManagementController::class, 'project_purchases_request_create'])->name('projectmanagement.purchases_request.create');
    Route::post('/project/purchases_request/{project_id}/store', [ProjectManagementController::class, 'project_purchases_request_store'])->name('projectmanagement.purchases_request.store');
    Route::get('/project/{project_id?}/purchases_request/edit/{id}', [ProjectManagementController::class, 'project_purchases_request_edit'])->name('projectmanagement.purchases_request.edit');



    Route::post('/project/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'store'])->name('projectmanagement.storeAdditionalCost');

    Route::post('/project/purchases_request/{project_id}/static_costs', [StaticCostsController::class, 'store'])->name('projectmanagement.storeStaticCost');
    


    //Project product
    Route::get('/project/warehouse_products/{project}/{warehouse}', [ProjectManagementController::class, 'warehouse_products'])->name('projectmanagement.warehouse_products');
    Route::get('/project/inventory_products/{inventory}', [ProjectManagementController::class, 'inventory_products'])->name('projectmanagement.inventory_products');

    Route::post('/project/products/store', [ProjectManagementController::class, 'project_product_store'])->name('projectmanagement.products.store');
    Route::put('/project/products/update/{project_product}', [ProjectManagementController::class, 'project_product_update'])->name('projectmanagement.products.update');
    Route::delete('/project/warehouse_products/{assigned}', [ProjectManagementController::class, 'warehouse_products_delete'])->name('projectmanagement.products.delete');

    Route::post('/project/purchases_request/update/due_date', [ProjectManagementController::class, 'project_purchases_request_update_due_date'])->name('projectmanagement.update_due_date');
    

    //Project liquidate
    Route::get('/project/products/{project_id}/{project_entry}', [LiquidationController::class, 'liquidateForm'])->name('projectmanagement.liquidate.form');
    Route::post('/project/products/{project_id}/{project_entry}/liquidate', [LiquidationController::class, 'liquidate'])->name('projectmanagement.liquidate.post');

    //Codes
    Route::post('/preprojects/codes/post', [PreProjectController::class, 'postCode'])->name('preprojects.codes.post');
    Route::put('/preprojects/codes/{code}/put', [PreProjectController::class, 'putCode'])->name('preprojects.codes.put');
    Route::delete('/preprojects/codes/{code}/delete', [PreProjectController::class, 'deleteCode'])->name('preprojects.codes.delete');

    //Titles
    Route::post('/preprojects/titles/post', [PreProjectController::class, 'postTitle'])->name('preprojects.titles.post');
    Route::put('/preprojects/titles/{title}/put', [PreProjectController::class, 'putTitle'])->name('preprojects.titles.put');
    Route::delete('/preprojects/titles/{title}/delete', [PreProjectController::class, 'deleteTitle'])->name('preprojects.titles.delete');


    //Member Cicsa Sections 
    Route::post('/cicsa/member/store', [CicsaSectionController::class, 'storeSubSection'])->name('sections.cicsa.member.store');
    Route::post('/cicsa/store', [CicsaSectionController::class, 'storeSection'])->name('sections.cicsa.section.store');




    Route::get('/project-document-gestion/{path?}/{project_id?}', [ProjectDocumentController::class, 'project_doc_index'])->name('project.document.index');

    
});

Route::middleware('permission:ProjectManager|Project')->group(function () {
    //Customers
    Route::any('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/search', [CustomersController::class, 'index'])->name('customers.search');

    //Codes and Titles
    Route::get('/preprojects/codes', [PreProjectController::class, 'showCodes'])->name('preprojects.codes');
    Route::get('/preprojects/titles', [PreProjectController::class, 'showTitles'])->name('preprojects.titles');

    //PreProjects
    Route::get('/preprojects', [PreProjectController::class, 'index'])->name('preprojects.index');
    Route::post('/preprojects', [PreProjectController::class, 'index'])->name('preprojects.index');

    //Preproject image
    Route::get('/preprojects/{preproject_id}/report/image', [PreProjectController::class, 'index_image'])->name('preprojects.imagereport.index');
    Route::get('/preprojects/{preproject_id}/report/code/image', [PreProjectController::class, 'filterCodePhoto'])->name('preprojects.report.images');
    Route::get('/preprojects/report/showimage/{image}', [PreProjectController::class, 'show_image'])->name('preprojects.imagereport.show');
    Route::get('/preprojects/{preproject_id}/report/download_image', [PreProjectController::class, 'download_image'])->name('preprojects.imagereport.download');
    Route::get('/preprojects/{preproject_id}/report/download_report', [PreProjectController::class, 'download_report'])->name('preprojects.report.download');

    //Photographic report
    Route::get('/preprojects/{preproject_id}/photoreport', [PreProjectController::class, 'photoreport_index'])->name('preprojects.photoreport.index');
    Route::get('/preprojects/photoreport_download/{report_name}', [PreProjectController::class, 'downloadPR'])->name('preprojects.photoreport.download');
    Route::get('/preprojects/photoreport_show/{pr_id}', [PreProjectController::class, 'showPR'])->name('preprojects.photoreport.show');
    Route::get('/preprojects/photoreport_download_pdf/{pr_id}', [PreProjectController::class, 'download_pdf_PR'])->name('preprojects.photoreport_pdf.download');

    //Preproject products
    Route::get('/preprojects/products/{preproject}', [PreProjectController::class, 'preproject_products_index'])->name('preprojects.products');

    //Preproject purchase request
    Route::get('/preprojects/request/{id}', [PreProjectController::class, 'request'])->name('preprojects.request.index');
    Route::get('/preprojects/request/{id}/details', [PreProjectController::class, 'request_shopping_details'])->name('preprojects.request.details');

    //Preproject purchase quote
    Route::get('/preprojects/purchase_quote/{id}', [PreProjectController::class, 'purchase_quote'])->name('preprojects.purchase_quote');
    Route::get('/preprojects/purchase_quote/details/{id}', [PreProjectController::class, 'purchase_quote_details'])->name('preprojects.purchase.quote.details');


    //Project
    Route::any('/project', [ProjectManagementController::class, 'index'])->name('projectmanagement.index');
    Route::any('/project/historial', [ProjectManagementController::class, 'historial'])->name('projectmanagement.historial');

    //Project calendar
    Route::get('/calendarProjects', [CalendarController::class, 'index'])->name('projectscalendar.index');
    Route::get('/calendarTasks/{project}', [CalendarController::class, 'show'])->name('projectscalendar.show');

    //Project task
    Route::get('/tasks/{id?}', [TaskManagementController::class, 'index'])->name('tasks.index');
    Route::get('/projecmanagement/task/edit/{taskId}', [TaskManagementController::class, 'edit'])->name('tasks.show');

    //Project resources
    Route::get('/project/resources/{project_id}', [ProjectManagementController::class, 'project_resources'])->name('projectmanagement.resources');

    //Project purchase request
    Route::get('/project/purchases_request/{project_id}', [ProjectManagementController::class, 'project_purchases_request_index'])->name('projectmanagement.purchases_request.index');
    Route::get('/project/purchases_request/details/{id}', [ProjectManagementController::class, 'project_purchases_request_details'])->name('projectmanagement.purchases_request.details');

    
    
    Route::get('/project/expenses/{project_id}', [ProjectManagementController::class, 'project_expenses'])->name('projectmanagement.expenses');
    Route::get('/project/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'index'])->name('projectmanagement.additionalCosts');
    Route::get('/additionalcost_photo/{additional_cost_id}', [AdditionalCostsController::class, 'download_ac_photo'])->name('additionalcost.archive');
    Route::post('/additionalcost_advancesearch/{project_id}', [AdditionalCostsController::class, 'search_costs'])->name('additionalcost.advance.search');


    Route::get('/project/purchases_request/{project_id}/static_costs', [StaticCostsController::class, 'index'])->name('projectmanagement.staticCosts');
    Route::get('/staticcost_photo/{additional_cost_id}', [StaticCostsController::class, 'download_ac_photo'])->name('staticcost.archive');
    Route::post('/staticcost_advancesearch/{project_id}', [StaticCostsController::class, 'search_costs'])->name('staticcost.advance.search');






    //Project product
    Route::get('/project/products/{project_id}', [ProjectManagementController::class, 'project_product_index'])->name('projectmanagement.products');

    //Project liquidate
    Route::get('/project/{project_id}/products/liquidate', [LiquidationController::class, 'index'])->name('projectmanagement.liquidate');
    Route::get('/project/{project_id}/product/liquidateHistory', [LiquidationController::class, 'history'])->name('projectmanagement.liquidate.history');

    //Member Cicsa Sections 
    Route::get('/member/cicsa', [CicsaSectionController::class, 'showSubSections'])->name('member.cicsa');
    Route::get('/member/cicsa/show/{subSection}', [CicsaSectionController::class, 'showSubSection'])->name('member.cicsa.show');

    Route::get('/member/cicsa/sections', [CicsaSectionController::class, 'showSections'])->name('cicsa.sections');

    Route::get('/sections/cicsa/alarm', [CicsaSectionController::class, 'alarm'])->name('member.cicsa.alarm');



    //pint auto
    Route::get('/preproject/auto-create/pint', [ProjectPintController::class, 'pint_create_project'])->name('project.auto.pint');
    Route::post('/preproject/auto-store/pint', [ProjectPintController::class, 'pint_store_project'])->name('project.auto_store.pint');
    Route::post('/product-CPE/', [ProjectPintController::class, 'sameCPEProducts'])->name('pint_project.products.cpe');


    Route::get('/additionalcost_photo/{additional_cost_id}', [AdditionalCostsController::class, 'download_ac_photo'])->name('additionalcost.archive');

});




