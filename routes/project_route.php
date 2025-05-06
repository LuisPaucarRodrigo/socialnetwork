<?php

use App\Constants\RolesConstants;
use App\Http\Controllers\HttpController;
use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\BacklogController;
use App\Http\Controllers\ProjectArea\CalendarController;
use App\Http\Controllers\ProjectArea\ChecklistsController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\ProjectArea\CustomersController;
use App\Http\Controllers\ProjectArea\LiquidationController;
use App\Http\Controllers\ProjectArea\MonthProjectController;
use App\Http\Controllers\ProjectArea\PextController;
use App\Http\Controllers\ProjectArea\PreProjectController;
use App\Http\Controllers\ProjectArea\ProjectDocumentController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\ProjectPintController;
use App\Http\Controllers\ProjectArea\StaticCostsController;
use App\Http\Controllers\ProjectArea\TaskManagementController;
use App\Http\Controllers\ProjectArea\ServicesLiquidationsController;
use App\Http\Controllers\ProjectArea\AdministrativeCostsController;
use App\Enums\Permissions\ProjectPermissions;
use App\Http\Controllers\Services\SharePointController;
use Illuminate\Support\Facades\Route;

Route::get('/sharepoint/index_cost_line', [SharePointController::class, 'index'])->name('sharepoint.index');
Route::post('/import_excel_expenses/expenses', [PextController::class, 'import_excel_expenses'])->name('importar.excel.expenses');

//new permissions routes

Route::post('/preprojects/{preproject}/update', [PreProjectController::class, 'update'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_UPDATE->value)
    ->name('preprojects.update');

Route::delete('/preprojects/{preproject}/destroy', [PreProjectController::class, 'destroy'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_DELETE->value)
    ->name('preprojects.destroy');

Route::post('/preprojects/photoreport_update/{photoreport}', [PreProjectController::class, 'photoreport_update'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_PHOTO_REPORT_UPDATE->value)
    ->name('preprojects.photoreport.update');

Route::delete('/preprojects/photoreport_delete/{photoreport}', [PreProjectController::class, 'photoreport_delete'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_PHOTO_REPORT_DELETE->value)
    ->name('preprojects.photoreport.delete');

Route::delete('/preprojects/providers_quotes/delete/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_delete'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_PROVIDERS_QUOTES_DELETE->value)
    ->name('preprojects.providersquotes.delete');

Route::post('/preprojects/quote_item_delete', [PreProjectController::class, 'quote_item_delete'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_QUOTE_ITEM_DELETE->value)
    ->name('preprojects.quote.item.delete');

Route::post('/preprojects/quote_item_store', [PreProjectController::class, 'quote_item_store'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_QUOTE_ITEM_STORE->value)
    ->name('preprojects.quote.item.store');

Route::post('/preprojects/quote_product_store', [PreProjectController::class, 'quote_product_store'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_QUOTE_PRODUCT_STORE->value)
    ->name('preprojects.quote.product.store');

Route::delete('/preprojects/quote_product_delete/{quote_product_id}', [PreProjectController::class, 'quote_product_delete'])
    ->middleware('permission:' . ProjectPermissions::PREPROJECT_QUOTE_PRODUCT_DELETE->value)
    ->name('preprojects.quote.product.delete');

Route::get('/projectmanagement/update/{project_id}/type/{type?}', [ProjectManagementController::class, 'project_create'])
    ->middleware('permission:' . ProjectPermissions::PROJECT_UPDATE->value)
    ->name('projectmanagement.update');

Route::delete('/projectmanagement/delete/{project_id}', [ProjectManagementController::class, 'project_destroy'])
    ->middleware('permission:' . ProjectPermissions::PROJECT_DELETE->value)
    ->name('projectmanagement.delete');

Route::delete('/project/update/delete-employee/{pivot_id}', [ProjectManagementController::class, 'project_delete_employee'])
    ->middleware('permission:' . ProjectPermissions::PROJECT_DELETE_EMPLOYEE->value)
    ->name('projectmanagement.delete.employee');

// TASKS
Route::post('/edittask/delete', [TaskManagementController::class, 'delete_employee'])
    ->middleware('permission:' . ProjectPermissions::TASK_DELETE_EMPLOYEE->value)
    ->name('tasks.delete.employee');

Route::delete('/deletetask/{taskId}', [TaskManagementController::class, 'delete_task'])
    ->middleware('permission:' . ProjectPermissions::TASK_DELETE->value)
    ->name('tasks.delete');

// CICSA SUBSECTION
Route::put('/cicsaSubSections/{subSection}/update', [CicsaSectionController::class, 'updateSubSection'])
    ->middleware('permission:' . ProjectPermissions::CICSA_SUBSECTION_UPDATE->value)
    ->name('sections.cicsaUpdateSubSection');

Route::delete('/cicsaSubSections/{subSection}/delete', [CicsaSectionController::class, 'destroySubSection'])
    ->middleware('permission:' . ProjectPermissions::CICSA_SUBSECTION_DELETE->value)
    ->name('sections.cicsaDestroySubSection');

Route::delete('/cicsaSections/{section}', [CicsaSectionController::class, 'destroySection'])
    ->middleware('permission:' . ProjectPermissions::CICSA_SECTION_DELETE->value)
    ->name('sections.cicsaDestroySection');







Route::middleware('permission:' . implode('|', RolesConstants::PROJECT_MODULE))->group(function () {
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
    Route::get('/preprojects/create/{type}/{preproject_id?}', [PreProjectController::class, 'create'])->name('preprojects.create');
    Route::post('/preprojects/store', [PreProjectController::class, 'store'])->name('preprojects.store');

    //Assign users
    Route::post('/preprojects/assignUser', [PreProjectController::class, 'preproject_users'])->name('preprojects.assign.users');

    //Preproject images
    Route::put('/preprojects/report/stages/store/{preproject_id}', [PreProjectController::class, 'stages_store'])->name('preprojects.stages.store');
    Route::delete('/preprojects/report/stages/{title_id}/delete', [PreProjectController::class, 'delete_stages'])->name('preprojects.stages.delete');
    Route::put('/preprojects/{preproject_image_id}/report/image', [PreProjectController::class, 'approve_reject_image'])->name('preprojects.imagereport.approveReject');
    Route::get('/preprojects/{preproject_code_id}/codereport', [PreProjectController::class, 'approve_code'])->name('preprojects.codereport.approveCode');
    Route::get('/preprojects/{preproject_title_id}/titlereport', [PreProjectController::class, 'approve_title'])->name('preprojects.codereport.approveTitle');
    Route::get('/preprojects/{code_id}/approve_images', [PreProjectController::class, 'approve_images'])->name('preprojects.codereport.approveImages');
    Route::delete('/preprojects/{preproject_id}/report/delete', [PreProjectController::class, 'delete_image'])->name('preprojects.imagereport.delete');
    Route::get('/preprojects/{preproject_title_id}/download/kmz', [PreProjectController::class, 'downloadKmz'])->name('preprojects.download.kmz');

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

    //Pext Project
    // Route::post('/projectPext/storeOrUpdate/{pext_id?}', [PextController::class, 'storeOrUpdate'])->name('projectmanagement.pext.storeOrUpdate');
    Route::post('/projectPext/storeProjectAndAssignation', [PextController::class, 'storeProjectAndAssignation'])->name('projectmanagement.pext.storeProjectAndAssignation');
    // Route::get('/projectPext/requestCicsa/{zone?}', [PextController::class, 'requestCicsa'])->name('projectmanagement.pext.requestCicsa');


    Route::post('/projectPext/expenses/storeOrUpdate/{expense_id?}', [PextController::class, 'expenses_storeOrUpdate'])->name('pext.expenses.storeOrUpdate');
    Route::delete('/projectPext/expenses/delete/{expense_id}', [PextController::class, 'expenses_delete'])->name('pext.expenses.delete');
    Route::put('/projectPext/expenses/expenseValidate/{expense_id}', [PextController::class, 'expense_validate'])->name('projectmanagement.pext.expenses.validate');

    // Route::get('/projectPext/monthly', [PextController::class, 'onthlyExpensePext'])->name('pext.monthly');


    //Project
    Route::get('/project/create', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.create');
    Route::post('/project/store', [ProjectManagementController::class, 'project_store'])->name('projectmanagement.store');
    Route::get('/projectmanagement/update/{project_id}/type/{type?}', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.update');
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


    //AdditionalCosts && StaticCosts
    Route::post('/project/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'store'])->name('projectmanagement.storeAdditionalCost');
    Route::post('/project/additional_costs/import/{project_id}', [AdditionalCostsController::class, 'import'])->name('projectmanagement.importAdditionalCost');
    Route::post('/project/purchases_request/{project_id}/static_costs', [StaticCostsController::class, 'store'])->name('projectmanagement.storeStaticCost');
    Route::post('/project/additional_cost/validate/{ac_id}', [AdditionalCostsController::class, 'validateRegister'])->name('projectmanagement.validateAdditionalCost');
    Route::get('/project/expenses/{project_id}', [ProjectManagementController::class, 'project_expenses'])->name('projectmanagement.expenses');


    //months of year of project
    Route::get('/project_last12_utilitites/{project_id}', [ProjectManagementController::class, 'projects_year_profit'])->name('projectmanagement.last12.utilities');
    Route::get('/project_zone_expenses_chart/{project_id}', [ProjectManagementController::class, 'project_zone_expenses'])->name('projectmanagement.zoneexpenses.chart');



    Route::get('/descargar_zip_add/{project_id}', [AdditionalCostsController::class, 'downloadImages'])->name('zip.additional.descargar');
    Route::get('/descargar_zip_static/{project_id}', [StaticCostsController::class, 'downloadImages'])->name('zip.static.descargar');


    //massive costs actions
    Route::post('/project/additional_costs_massive_update/', [AdditionalCostsController::class, 'masiveUpdate'])->name('projectmanagement.additionalCosts.massiveUpdate');
    Route::post('/project/static_costs_massive_swap/', [AdditionalCostsController::class, 'swapCosts'])->name('projectmanagement.additionalCosts.swapCosts');
    Route::post('/project/additionals_to_addproject_massive_swap/', [AdditionalCostsController::class, 'swapCostsToAdditionalProject'])->name('projectmanagement.addctoaddproject.swapCosts');
    Route::post('/project/static_costs_massive_update/', [StaticCostsController::class, 'masiveUpdate'])->name('projectmanagement.staticCosts.massiveUpdate');

    Route::get('/project_get_regular_projects', [AdditionalCostsController::class, 'getRegularProjects'])->name('projectmanagement.regularprojects.all');
    Route::post('/project_swap_regular_projects', [AdditionalCostsController::class, 'swapCostsToRegularProject'])->name('projectmanagement.regularproject.swapCosts');



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
    Route::post('/preprojects/codes/post', [PreProjectController::class, 'storeCode'])->name('preprojects.codes.post');
    Route::put('/preprojects/codes/{code}/put', [PreProjectController::class, 'updateCode'])->name('preprojects.codes.put');
    Route::delete('/preprojects/codes/{code}/delete', [PreProjectController::class, 'deleteCode'])->name('preprojects.codes.delete');

    Route::get('/preprojects/codes/{code_id}/images/show', [PreProjectController::class, 'indexImages'])->name('preprojects.code.images.index');
    Route::post('/preprojects/codes/images/store', [PreProjectController::class, 'storeCodeImages'])->name('preprojects.code.images.store');
    Route::get('/preprojects/codes/images/{image_id}/show', [PreProjectController::class, 'show_code_image'])->name('preprojects.code.images.show');
    Route::delete('/preprojects/codes/images/{image_id}/delete', [PreProjectController::class, 'deleteCodeImages'])->name('preprojects.code.images.delete');

    //Titles
    Route::post('/preprojects/titles/post', [PreProjectController::class, 'postTitle'])->name('preprojects.titles.post');
    Route::put('/preprojects/titles/{title}/put', [PreProjectController::class, 'putTitle'])->name('preprojects.titles.put');
    Route::delete('/preprojects/titles/{title}/delete', [PreProjectController::class, 'deleteTitle'])->name('preprojects.titles.delete');


    //Member Cicsa Sections 
    Route::post('/cicsa/member/store', [CicsaSectionController::class, 'storeSubSection'])->name('sections.cicsa.member.store');
    Route::post('/cicsa/store', [CicsaSectionController::class, 'storeSection'])->name('sections.cicsa.section.store');




    Route::get('/project-document-gestion', [ProjectDocumentController::class, 'project_doc_index'])->name('project.document.index');
    Route::post('/project-document-gestion/store/{path?}', [ProjectDocumentController::class, 'project_doc_store'])->name('project.document.store');
    Route::post('/project-document-gestion/folder_archive_delete', [ProjectDocumentController::class, 'project_doc_delete'])->name('project.folder.delete');
    Route::get('/project-document-gestion/folder_archive_dowload', [ProjectDocumentController::class, 'project_doc_download'])->name('project.folder.download');

    Route::get('/project-backlog', [BacklogController::class, 'index'])->name('project.backlog.index');
    Route::get('/project-backlog/autcomplete', [BacklogController::class, 'autocomplete'])->name('project.backlog.autocomplete');
    Route::post('/project-backlog/store', [BacklogController::class, 'store'])->name('project.backlog.store');
    Route::delete('/project-backlog/delete/{backlog_id}', [BacklogController::class, 'destroy'])->name('project.backlog.destroy');

    //Checklist

    Route::get('/checklist', [ChecklistsController::class, 'index'])->name('checklist.index');
    Route::get('/checklist/car', [ChecklistsController::class, 'car_index'])->name('checklist.car.index');
    Route::get('/checklist/car/{id}/{photoProp}', [ChecklistsController::class, 'car_photo'])->name('checklist.car.photo');
    Route::get('/checklist/toolkit/{id}/{photoProp}', [ChecklistsController::class, 'toolkit_photo'])->name('checklist.toolkit.photo');
    Route::get('/checklist/dailytoolkit', [ChecklistsController::class, 'dailytoolkit_index'])->name('checklist.dailytoolkit.index');
    Route::get('/checklist/epp', [ChecklistsController::class, 'epp_index'])->name('checklist.epp.index');
    Route::get('/checklist/toolkit', [ChecklistsController::class, 'toolkit_index'])->name('checklist.toolkit.index');

    Route::delete('/checklist/car/{id}/destroy', [ChecklistsController::class, 'car_destroy'])->name('checklist.car.destroy');
    Route::delete('/checklist/toolkit/{id}/destroy', [ChecklistsController::class, 'toolkit_destroy'])->name('checklist.toolkit.destroy');
    Route::delete('/checklist/dailytoolkit/{id}/destroy', [ChecklistsController::class, 'dailytoolkit_destroy'])->name('checklist.dailytoolkit.destroy');
    Route::delete('/checklist/epp/{id}/destroy', [ChecklistsController::class, 'epp_destroy'])->name('checklist.epp.destroy');


    //Administrative projects expenses
    Route::get('/monthProjects', [MonthProjectController::class, 'index'])->name('monthproject.index');
    Route::post('/monthProjects_store/{mp_id?}', [MonthProjectController::class, 'store'])->name('monthproject.store');
    Route::delete('/monthProjects_destroy/{mp_id}', [MonthProjectController::class, 'destroy'])->name('monthproject.destroy');


    Route::post('/project/purchases_request/{month_project_id}/administrative_costs', [AdministrativeCostsController::class, 'store'])->name('projectmanagement.storeAdministrativeCost');
    Route::get('/descargar_zip_administrative/{month_project_id}', [AdministrativeCostsController::class, 'downloadImages'])->name('zip.administrative.descargar');
    Route::post('/project/administrative_costs_massive_update/', [AdministrativeCostsController::class, 'masiveUpdate'])->name('projectmanagement.administrativeCosts.massiveUpdate');
    Route::get('/project/purchases_request/{month_project_id}/administrative_costs', [AdministrativeCostsController::class, 'index'])->name('projectmanagement.administrativeCosts');
    Route::get('/administrativecosts_photo/{static_cost_id}', [AdministrativeCostsController::class, 'download_ac_photo'])->name('administrativeCosts.archive');
    Route::post('/administrativecosts_advancesearch/{month_project_id}', [AdministrativeCostsController::class, 'search_costs'])->name('administrativeCosts.advance.search');
    Route::get('/administrativecosts/excel_export/{month_project_id}', [AdministrativeCostsController::class, 'export'])->name('administrativeCosts.excel.export');
    Route::post('/projectmanagement/purchases_request/administrative_costs/{additional_cost}/update', [AdministrativeCostsController::class, 'update'])->name('projectmanagement.updateAdministrativeCost');
    Route::delete('/projectmanagement/purchases_request/{month_project_id}/administrative_costs/{additional_cost}/destroy', [AdministrativeCostsController::class, 'destroy'])->name('projectmanagement.deleteAdministrativeCost');










});

Route::middleware('permission:' . implode('|', RolesConstants::PROJECT_MODULE))->group(function () {
    //Customers
    Route::any('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/search', [CustomersController::class, 'index'])->name('customers.search');

    //Codes and Titles
    Route::get('/preprojects/codes', [PreProjectController::class, 'showCodes'])->name('preprojects.codes');
    Route::get('/preprojects/titles', [PreProjectController::class, 'showTitles'])->name('preprojects.titles');

    //PreProjects PEXT
    Route::get('/preprojects_index/{type}/{preprojects_status?}', [PreProjectController::class, 'index'])->name('preprojects.index');
    Route::post('/preprojects_index/{type}/{preprojects_status?}', [PreProjectController::class, 'index'])->name('preprojects.index');


    //Preproject image
    Route::get('/preprojects/{preproject_id}/report/image', [PreProjectController::class, 'index_image'])->name('preprojects.imagereport.index');
    // Route::get('/preprojects/{preproject_id}/report/code/image', [PreProjectController::class, 'filterCodePhoto'])->name('preprojects.report.images');
    Route::get('/preprojects/report/showimage/{image}', [PreProjectController::class, 'show_image'])->name('preprojects.imagereport.show');
    Route::get('/preprojects/{preproject_id}/report/download_image', [PreProjectController::class, 'download_image'])->name('preprojects.imagereport.download');
    Route::get('/preprojects/{preproject_title_id}/report/download_report', [PreProjectController::class, 'download_report'])->name('preprojects.report.download');

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
    // Route::get('/projectPext/expenses/fixed/{cicsa_assignation_id}', [PextController::class, 'additional_expense_additional_index'])->name('projectmanagement.pext.expenses.fixed');

    //Project Pext
    Route::any('/projectPext/index', [PextController::class, 'index'])->name('projectmanagement.pext.index');
    Route::get('/projectPext/projectOrPreproject', [PextController::class, 'requestProjectOrPreproject'])->name('projectmanagement.pext.requestProjectOrPreproject');
    Route::any('/projectPext/projectOrPreproject/historial_pext', [PextController::class, 'historial_pext'])->name('projectmanagement.pext.historial');

    // Route::get('/projectPext/export/expenses', [PextController::class, 'export_expenses'])->name('projectmanagement.pext.export.expenses');

    Route::get('/projectPext/expenses/monthly/{project_id}/index/{fixedOrAdditional}/status/{status?}', [PextController::class, 'index_expenses'])->name('projectmanagement.pext.expenses.index');
    Route::get('/projectPext/expenses/showImage/{expense_id}', [PextController::class, 'expense_show_image'])->name('projectmanagement.pext.expenses.image.show');
    Route::get('/projectPext/expenses/{project_id}/export/{fixedOrAdditional}', [PextController::class, 'expense_export'])->name('projectmanagement.pext.expenses.export');
    Route::get('/projectPext/expenses/export/general/{fixedOrAdditional}/cost_line/{cost_line}', [PextController::class, 'expense_export_general'])->name('projectmanagement.pext.expenses.general.export');

    //Project Pext Additional
    Route::any('/projectPext/additional_index/{type}/{searchCondition?}', [PextController::class, 'index_additional'])->name('projectmanagement.pext.additional.index');
    Route::any('/projectPext/additional_rejected/index/{type}', [PextController::class, 'index_additional_rejected'])->name('projectmanagement.pext.additional.index_rejected');
    Route::post('/projectPext/additional_reject/{pa_id}', [PextController::class, 'rejectProjectAdditional'])->name('projectmanagement.pext.additional.reject');


    Route::post('/projectPext/additional/store_quote/{project_quote_id?}', [PextController::class, 'store_quote'])->name('projectmanagement.pext.store.quote');
    Route::get('/projectPext/additional/export/quote/{project_id}', [PextController::class, 'export_quote'])->name('projectmanagement.pext.export.pdf.quote');
    Route::post('/projectPext/additional/store/{cicsa_assignation_id?}', [PextController::class, 'updateOrStoreAdditional'])->name('projectmanagement.pext.additional.store');
    Route::get('/projectPext/additionalOrFixed/expenses/{project_id}/index/{fixedOrAdditional}/{type}', [PextController::class, 'additional_expense_index'])->name('pext.additional.expense.index');
    Route::get('/projectPext/additionalOrFixed/expenses/general/{fixedOrAdditional}/index/{type}', [PextController::class, 'additional_expense_index_general'])->name('pext.additional.expense.general.index');
    Route::post('/projectPext/additionalOrFixed/getCicsaAssignation/search_zone', [PextController::class, 'getCicsaAssignation'])->name('pext.additional.expense.general.getCicsaAssignation');

    Route::post('/projectPext/additionalOrFixed/expense/search/{project_id}', [PextController::class, 'search_advance_monthly_or_additional_expense'])->name('pext.monthly.additional.expense.search_advance');
    Route::post('/projectPext/additionalOrFixed/expense/general/search', [PextController::class, 'search_advance_additional_expense_general'])->name('pext.monthly.additional.expense.general.search_advance');
    Route::post('/projectPext/massive_update', [PextController::class, 'masiveUpdate'])->name('projectmanagement.pext.massiveUpdate');
    Route::post('/projectPext/massive_update_swap', [PextController::class, 'masiveUpdateSwap'])->name('projectmanagement.pext.massiveUpdate.swap');

    Route::get('/projectPext/expense_dashboard/{project_id}', [PextController::class, 'expense_dashboard'])->name('projectmanagement.pext.expense_dashboard');
    Route::post('/projectPext/expense_type_zone', [PextController::class, 'expense_type_zone'])->name('projectmanagement.pext.expenset_type_zone');
    Route::get('/projectPext/expense_dashboard_bar/{project_id}', [PextController::class, 'barChart'])->name('projectmanagement.pext.expense_dashboard_bar');

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



    Route::get('/project/purchases_request_index/{project_id}/additional_costs', [AdditionalCostsController::class, 'index'])->name('projectmanagement.additionalCosts');
    Route::get('/project/purchases_request/{project_id}/additional_costs/rejected', [AdditionalCostsController::class, 'indexRejected'])->name('projectmanagement.additionalCosts.rejected');
    Route::get('/additionalcost_photo/{additional_cost_id}', [AdditionalCostsController::class, 'download_ac_photo'])->name('additionalcost.archive');
    Route::post('/additionalcost_advancesearch/{project_id}', [AdditionalCostsController::class, 'search_costs'])->name('additionalcost.advance.search');


    Route::get('/project/purchases_request/{project_id}/static_costs', [StaticCostsController::class, 'index'])->name('projectmanagement.staticCosts');
    Route::get('/staticcost_photo/{static_cost_id}', [StaticCostsController::class, 'download_ac_photo'])->name('staticcost.archive');
    Route::post('/staticcost_advancesearch/{project_id}', [StaticCostsController::class, 'search_costs'])->name('staticcost.advance.search');

    Route::post('/ad_st_costs_details', [ProjectManagementController::class, 'project_expense_details'])->name('project.expenses.zones.details');




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
    Route::get('/preproject/auto-create/pint/{type}', [ProjectPintController::class, 'pint_create_project'])->name('project.auto.pint');
    Route::get('/preproject/auto-create/search_employees/{cc_id}', [ProjectPintController::class, 'getEmployees'])->name('project.auto.pint.getEmployees');
    Route::post('/preproject/auto-store/pint', [ProjectPintController::class, 'pint_store_project'])->name('project.auto_store.pint');
    Route::post('/product-CPE/pint', [ProjectPintController::class, 'sameCPEProducts'])->name('pint_project.products.cpe');

    //pext auto
    Route::get('/preproject/auto-create/pext/{type}', [ProjectPintController::class, 'pext_create_project'])->name('project.auto.pext');
    Route::post('/preproject/auto-store/pext', [ProjectPintController::class, 'pext_store_project'])->name('project.auto_store.pext');
    Route::post('/product-CPE/pext', [ProjectPintController::class, 'sameCPEProductsPext'])->name('pext_project.products.cpe');

    //Costs Export
    Route::get('/additionalcost_photo/{additional_cost_id}', [AdditionalCostsController::class, 'download_ac_photo'])->name('additionalcost.archive');
    Route::get('/additionalcosts/excel_export/{project_id}', [AdditionalCostsController::class, 'export'])->name('additionalcost.excel.export');



    Route::post('/projectmanagement/purchases_request/additional_costs/{additional_cost}/update', [AdditionalCostsController::class, 'update'])->name('projectmanagement.updateAdditionalCost');
    Route::delete('/projectmanagement/purchases_request/{project_id}/additional_costs/{additional_cost}/destroy', [AdditionalCostsController::class, 'destroy'])->name('projectmanagement.deleteAdditionalCost');


    Route::get('/staticcosts/excel_export/{project_id}', [StaticCostsController::class, 'export'])->name('staticcost.excel.export');
    Route::post('/projectmanagement/purchases_request/static_costs/{additional_cost}/update', [StaticCostsController::class, 'update'])->name('projectmanagement.updateStaticCost');
    Route::delete('/projectmanagement/purchases_request/{project_id}/static_costs/{additional_cost}/destroy', [StaticCostsController::class, 'destroy'])->name('projectmanagement.deleteStaticCost');

});
