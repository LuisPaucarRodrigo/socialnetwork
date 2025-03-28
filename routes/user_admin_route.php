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

Route::middleware('permission:UserManager')->group(function () {
   
    Route::get('users/linkEmployee/{user}', [UserController::class, 'linkEmployee'])->name('users.linkEmployee');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('users/delete/{id}', [UserController::class, 'delete'])->name('users.destroy');

    Route::get('rols', [ManagementRolsController::class, 'rols_index'])->name('rols.index');
    Route::post('rols/store', [ManagementRolsController::class, 'store'])->name('rols.store');
    Route::post('rols/update/{id}', [ManagementRolsController::class, 'update'])->name('rols.update');
    Route::delete('rols/delete/{id}', [ManagementRolsController::class, 'delete'])->name('rols.delete');
    Route::get('rols/details/{id}', [ManagementRolsController::class, 'details'])->name('rols.details');


    //PROYECTOS
    //preprojects
    Route::post('/preprojects/{preproject}/update', [PreProjectController::class, 'update'])->name('preprojects.update');
    Route::delete('/preprojects/{preproject}/destroy', [PreProjectController::class, 'destroy'])->name('preprojects.destroy');
    Route::post('/preprojects/photoreport_update/{photoreport}', [PreProjectController::class, 'photoreport_update'])->name('preprojects.photoreport.update');
    Route::delete('/preprojects/photoreport_delete/{photoreport}', [PreProjectController::class, 'photoreport_delete'])->name('preprojects.photoreport.delete');
    Route::delete('/preprojects/providers_quotes/delete/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_delete'])->name('preprojects.providersquotes.delete');
    Route::post('/preprojects/quote_item_delete', [PreProjectController::class, 'quote_item_delete'])->name('preprojects.quote.item.delete');
    Route::post('/preprojects/quote_item_store', [PreProjectController::class, 'quote_item_store'])->name('preprojects.quote.item.store');
    Route::post('/preprojects/quote_product_store', [PreProjectController::class, 'quote_product_store'])->name('preprojects.quote.product.store');
    Route::delete('/preprojects/quote_product_delete/{quote_product_id}', [PreProjectController::class, 'quote_product_delete'])->name('preprojects.quote.product.delete');

    //projects
    Route::get('/projectmanagement/update/{project_id}/type/{type?}', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.update');
    Route::delete('/projectmanagement/delete/{project_id}', [ProjectManagementController::class, 'project_destroy'])->name('projectmanagement.delete');
    Route::delete('/project/update/delete-employee/{pivot_id}', [ProjectManagementController::class, 'project_delete_employee'])->name('projectmanagement.delete.employee');
    Route::delete('/shopping_area/purchasesrequest/destroy/{id}', [PurchaseRequestController::class, 'destroy'])->name('purchasesrequest.destroy');


    



    //Tareas
    Route::post('/edittask/delete', [TaskManagementController::class, 'delete_employee'])->name('tasks.delete.employee');
    Route::delete('/deletetask/{taskId}', [TaskManagementController::class, 'delete_task'])->name('tasks.delete');
    //Cicsa subsection
    Route::put('/cicsaSubSections/{subSection}/update', [CicsaSectionController::class, 'updateSubSection'])->name('sections.cicsaUpdateSubSection');
    Route::delete('/cicsaSubSections/{subSection}/delete', [CicsaSectionController::class, 'destroySubSection'])->name('sections.cicsaDestroySubSection');
    Route::delete('/cicsaSections/{section}', [CicsaSectionController::class, 'destroySection'])->name('sections.cicsaDestroySection');











    //Special Warehouses
    Route::delete('/inventory/special_products/destroy/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_destroy'])->name('inventory.special_products.destroy');
    Route::delete('/inventory/special_dispatch_output_delete/{project_entry_output_id}', [SpecialWarehouseController::class, 'special_dispatch_output_destroy'])->name('inventory.special_dispatch_output.destroy');

    //HumanResource
    Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])->name('management.employees.destroy');
    Route::put('/management_employees/fired/{id}', [ManagementEmployees::class, 'fired'])->name('management.employees.fired');
    Route::put('/management_employees/{id}/reentry', [ManagementEmployees::class, 'reentry'])->name('management.employees.reentry');

    //Formation Development program
    Route::delete('/management_employees/formation_development/delete/{id}', [FormationDevelopment::class, 'formation_programs_destroy'])->name('management.employees.formation_development.delete');
    Route::delete('/management_employees/formation_development/delete-employee/{efp_id}', [FormationDevelopment::class, 'formation_programs_destroy_employee'])->name('management.employees.formation_development.employee.delete');

    //Training
    Route::delete('/management_employees/formation_development/trainings/delete/{id}', [FormationDevelopment::class, 'trainings_destroy'])->name('management.employees.formation_development.trainings.destroy');


    //Vacation

    Route::get('/management_vacation/information_additional/{vacation}/review', [VacationController::class, 'review'])->name('management.vacation.information.review');
    Route::post('/management_vacation/information_additional/reviewed/decline', [VacationController::class, 'reviewed_and_decline'])->name('management.vacation.information.reviewed_decline');
    Route::delete('/management_vacation/information_additional/{vacation}/delete', [VacationController::class, 'destroy'])->name('management.vacation.information.destroy');

    //Document
    Route::delete('/documents/{id}/delete', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::post('/purchasing_request_product_store', [PurchaseRequestController::class, 'purchasing_request_product_store'])->name('purchasing_request_product.store');
    Route::delete('/purchasing_request_product_delete/{purchasing_request_product_id}', [PurchaseRequestController::class, 'purchasing_request_product_delete'])->name('purchasing_request_product.delete');

    //Folders Validation
    Route::get('/document_management/folder_validation', [FolderController::class, 'folder_validation'])->name('documment.management.folders.validation');
    Route::post('/document_management/folder_validation/check/{folder_id}', [FolderController::class, 'folder_check'])->name('documment.management.folders.check');
    Route::delete('/document_management/folder_validation/invalidate/{folder_id}', [FolderController::class, 'folder_invalidate'])->name('documment.management.folders.invalidate');

    Route::post('/document_management/folder_permissions/{folder_area_id}', [FolderController::class, 'see_dowload_permission'])->name('documment.management.folders.permission.see_download');
    Route::post('/document_management/folder_permissions_create/{folder_area_id}', [FolderController::class, 'create_permission'])->name('documment.management.folders.permission.create');

    Route::post('/document_management/folder_permissions_add', [FolderController::class, 'folder_permission_add'])->name('documment.management.folders.permission.add');
    Route::delete('/document_management/folder_permissions_delete/{folder_area_id}', [FolderController::class, 'folder_permission_remove'])->name('documment.management.folders.permission.delete');

    Route::delete('document_management/folder_destroy/{folder_id}', [FolderController::class, 'folder_delete'])->name('document.management.folder.destroy');

    Route::get('/documment_management/folder_permission/{folder_id}', [FolderController::class, 'folder_permissions'])->name('documment.management.folders.permissions');

    //Inventory
    Route::put('/inventory/purchase_products/products/{purchase_product}/disable', [PurchaseProductsController::class, 'disable'])->name('inventory.purchaseproducts.disable');

    //Inventory Product Type and Active Type
    Route::post('/inventory/purchase_products/type_product/store', [PurchaseProductsController::class, 'typeProducts'])->name('inventory.purchaseproducts.typeProduct');
    Route::post('/inventory/purchase_products/resource_type/store', [PurchaseProductsController::class, 'resourceType'])->name('inventory.purchaseproducts.resourceType');

    //Resource
    Route::get('/inventory/resource/purchase_orders/create', [WarehousesController::class, 'resource_create'])->name('warehouses.resource.create');
    Route::post('/inventory/resource/purchase_orders/store', [WarehousesController::class, 'resource_store'])->name('warehouses.resource.store');

    //Providers
    Route::get('/shopping_area/providers/edit/{id}', [ProviderController::class, 'edit'])->name('providersmanagement.edit');
    Route::post('/shopping_area/providers/update/{id}', [ProviderController::class, 'update'])->name('providersmanagement.update');
    Route::delete('/shopping_area/providers/destroy/{id}', [ProviderController::class, 'destroy'])->name('providersmanagement.destroy');

    //Purchase request
    Route::get('/shopping_area/purchasesrequest/edit/{id}/{project_id?}', [PurchaseRequestController::class, 'edit'])->name('purchasesrequest.edit');
    Route::put('/shopping_area/purchasesrequest/update/{id}', [PurchaseRequestController::class, 'update'])->name('purchasesrequest.update');
});


Route::middleware('permission:UserManager|UserGestionManager|UserGestion')->group(function () {
    Route::get('users', [UserController::class, 'index_user'])->name('users.index');
    Route::post('users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('users/details/{id}', [UserController::class, 'details'])->name('users.details');
});