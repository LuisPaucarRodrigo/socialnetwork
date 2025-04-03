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
use App\Enums\Permissions\UserPermissions;


Route::middleware('permission:UserManager')->group(function () {

    // USERS
    Route::get('users/linkEmployee/{user}', [UserController::class, 'linkEmployee'])
        ->middleware('permission:' .UserPermissions::USER_LINK_EMPLOYEE->value)
        ->name('users.linkEmployee');

    Route::get('users/edit/{id}', [UserController::class, 'edit'])
        ->middleware('permission:' .UserPermissions::USER_EDIT->value)
        ->name('users.edit');

    Route::put('users/update/{id}', [UserController::class, 'update'])
        ->middleware('permission:' .UserPermissions::USER_UPDATE->value)
        ->name('users.update');

    Route::post('users/delete/{id}', [UserController::class, 'delete'])
        ->middleware('permission:' .UserPermissions::USER_DELETE->value)
        ->name('users.destroy');

    // ROLS
    Route::get('rols', [ManagementRolsController::class, 'rols_index'])
        ->middleware('permission:' .UserPermissions::ROL_VIEW->value)
        ->name('rols.index');

    Route::post('rols/store', [ManagementRolsController::class, 'store'])
        ->middleware('permission:' .UserPermissions::ROL_CREATE->value)
        ->name('rols.store');

    Route::post('rols/update/{id}', [ManagementRolsController::class, 'update'])
        ->middleware('permission:' .UserPermissions::ROL_UPDATE->value)
        ->name('rols.update');

    Route::delete('rols/delete/{id}', [ManagementRolsController::class, 'delete'])
        ->middleware('permission:' .UserPermissions::ROL_DELETE->value)
        ->name('rols.delete');

    Route::get('rols/details/{id}', [ManagementRolsController::class, 'details'])
        ->middleware('permission:' .UserPermissions::ROL_DETAILS->value)
        ->name('rols.details');

    // PREPROJECTS
    Route::post('/preprojects/{preproject}/update', [PreProjectController::class, 'update'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_UPDATE->value)
        ->name('preprojects.update');

    Route::delete('/preprojects/{preproject}/destroy', [PreProjectController::class, 'destroy'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_DELETE->value)
        ->name('preprojects.destroy');

    Route::post('/preprojects/photoreport_update/{photoreport}', [PreProjectController::class, 'photoreport_update'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_PHOTO_REPORT_UPDATE->value)
        ->name('preprojects.photoreport.update');

    Route::delete('/preprojects/photoreport_delete/{photoreport}', [PreProjectController::class, 'photoreport_delete'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_PHOTO_REPORT_DELETE->value)
        ->name('preprojects.photoreport.delete');

    Route::delete('/preprojects/providers_quotes/delete/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_delete'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_PROVIDERS_QUOTES_DELETE->value)
        ->name('preprojects.providersquotes.delete');

    Route::post('/preprojects/quote_item_delete', [PreProjectController::class, 'quote_item_delete'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_QUOTE_ITEM_DELETE->value)
        ->name('preprojects.quote.item.delete');

    Route::post('/preprojects/quote_item_store', [PreProjectController::class, 'quote_item_store'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_QUOTE_ITEM_STORE->value)
        ->name('preprojects.quote.item.store');

    Route::post('/preprojects/quote_product_store', [PreProjectController::class, 'quote_product_store'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_QUOTE_PRODUCT_STORE->value)
        ->name('preprojects.quote.product.store');

    Route::delete('/preprojects/quote_product_delete/{quote_product_id}', [PreProjectController::class, 'quote_product_delete'])
        ->middleware('permission:' .UserPermissions::PREPROJECT_QUOTE_PRODUCT_DELETE->value)
        ->name('preprojects.quote.product.delete');




    // PROJECTS
    Route::get('/projectmanagement/update/{project_id}/type/{type?}', [ProjectManagementController::class, 'project_create'])
        ->middleware('permission:' .UserPermissions::PROJECT_UPDATE->value)
        ->name('projectmanagement.update');

    Route::delete('/projectmanagement/delete/{project_id}', [ProjectManagementController::class, 'project_destroy'])
        ->middleware('permission:' .UserPermissions::PROJECT_DELETE->value)
        ->name('projectmanagement.delete');

    Route::delete('/project/update/delete-employee/{pivot_id}', [ProjectManagementController::class, 'project_delete_employee'])
        ->middleware('permission:' .UserPermissions::PROJECT_DELETE_EMPLOYEE->value)
        ->name('projectmanagement.delete.employee');

    Route::delete('/shopping_area/purchasesrequest/destroy/{id}', [PurchaseRequestController::class, 'destroy'])
        ->middleware('permission:' .UserPermissions::PURCHASE_REQUEST_DELETE->value)
        ->name('purchasesrequest.destroy');

    // TASKS
    Route::post('/edittask/delete', [TaskManagementController::class, 'delete_employee'])
        ->middleware('permission:' .UserPermissions::TASK_DELETE_EMPLOYEE->value)
        ->name('tasks.delete.employee');

    Route::delete('/deletetask/{taskId}', [TaskManagementController::class, 'delete_task'])
        ->middleware('permission:' .UserPermissions::TASK_DELETE->value)
        ->name('tasks.delete');

    // CICSA SUBSECTION
    Route::put('/cicsaSubSections/{subSection}/update', [CicsaSectionController::class, 'updateSubSection'])
        ->middleware('permission:' .UserPermissions::CICSA_SUBSECTION_UPDATE->value)
        ->name('sections.cicsaUpdateSubSection');

    Route::delete('/cicsaSubSections/{subSection}/delete', [CicsaSectionController::class, 'destroySubSection'])
        ->middleware('permission:' .UserPermissions::CICSA_SUBSECTION_DELETE->value)
        ->name('sections.cicsaDestroySubSection');

    Route::delete('/cicsaSections/{section}', [CicsaSectionController::class, 'destroySection'])
        ->middleware('permission:' .UserPermissions::CICSA_SECTION_DELETE->value)
        ->name('sections.cicsaDestroySection');


    // Special Warehouses
    Route::delete('/inventory/special_products/destroy/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_destroy'])
        ->middleware('permission:' .UserPermissions::SPECIAL_PRODUCTS_DELETE->value)
        ->name('inventory.special_products.destroy');

    Route::delete('/inventory/special_dispatch_output_delete/{project_entry_output_id}', [SpecialWarehouseController::class, 'special_dispatch_output_destroy'])
        ->middleware('permission:' .UserPermissions::SPECIAL_DISPATCH_OUTPUT_DELETE->value)
        ->name('inventory.special_dispatch_output.destroy');

    // Human Resource
    Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])
        ->middleware('permission:' .UserPermissions::MANAGEMENT_EMPLOYEES_DELETE->value)
        ->name('management.employees.destroy');

    Route::put('/management_employees/fired/{id}', [ManagementEmployees::class, 'fired'])
        ->middleware('permission:' .UserPermissions::MANAGEMENT_EMPLOYEES_FIRED->value)
        ->name('management.employees.fired');

    Route::put('/management_employees/{id}/reentry', [ManagementEmployees::class, 'reentry'])
        ->middleware('permission:' .UserPermissions::MANAGEMENT_EMPLOYEES_REENTRY->value)
        ->name('management.employees.reentry');

    // Formation Development Program
    Route::delete('/management_employees/formation_development/delete/{id}', [FormationDevelopment::class, 'formation_programs_destroy'])
        ->middleware('permission:' .UserPermissions::FORMATION_DEVELOPMENT_DELETE->value)
        ->name('management.employees.formation_development.delete');

    Route::delete('/management_employees/formation_development/delete-employee/{efp_id}', [FormationDevelopment::class, 'formation_programs_destroy_employee'])
        ->middleware('permission:' .UserPermissions::FORMATION_DEVELOPMENT_EMPLOYEE_DELETE->value)
        ->name('management.employees.formation_development.employee.delete');

    // Training
    Route::delete('/management_employees/formation_development/trainings/delete/{id}', [FormationDevelopment::class, 'trainings_destroy'])
        ->middleware('permission:' .UserPermissions::TRAININGS_DELETE->value)
        ->name('management.employees.formation_development.trainings.destroy');

    // Vacation
    Route::get('/management_vacation/information_additional/{vacation}/review', [VacationController::class, 'review'])
        ->middleware('permission:' .UserPermissions::MANAGEMENT_VACATION_REVIEW->value)
        ->name('management.vacation.information.review');

    Route::post('/management_vacation/information_additional/reviewed/decline', [VacationController::class, 'reviewed_and_decline'])
        ->middleware('permission:' .UserPermissions::MANAGEMENT_VACATION_REVIEW_DECLINE->value)
        ->name('management.vacation.information.reviewed_decline');

    Route::delete('/management_vacation/information_additional/{vacation}/delete', [VacationController::class, 'destroy'])
        ->middleware('permission:' .UserPermissions::MANAGEMENT_VACATION_DELETE->value)
        ->name('management.vacation.information.destroy');

    // Document
    Route::delete('/documents/{id}/delete', [DocumentController::class, 'destroy'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_DELETE->value)
        ->name('documents.destroy');

    // Purchasing Request Products
    Route::post('/purchasing_request_product_store', [PurchaseRequestController::class, 'purchasing_request_product_store'])
        ->middleware('permission:' .UserPermissions::PURCHASING_REQUEST_PRODUCT_STORE->value)
        ->name('purchasing_request_product.store');

    Route::delete('/purchasing_request_product_delete/{purchasing_request_product_id}', [PurchaseRequestController::class, 'purchasing_request_product_delete'])
        ->middleware('permission:' .UserPermissions::PURCHASING_REQUEST_PRODUCT_DELETE->value)
        ->name('purchasing_request_product.delete');








    // Folders Validation
    Route::get('/document_management/folder_validation', [FolderController::class, 'folder_validation'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_VALIDATION->value)
        ->name('documment.management.folders.validation');

    Route::post('/document_management/folder_validation/check/{folder_id}', [FolderController::class, 'folder_check'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_CHECK->value)
        ->name('documment.management.folders.check');

    Route::delete('/document_management/folder_validation/invalidate/{folder_id}', [FolderController::class, 'folder_invalidate'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_INVALIDATE->value)
        ->name('documment.management.folders.invalidate');

    Route::post('/document_management/folder_permissions/{folder_area_id}', [FolderController::class, 'see_dowload_permission'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_PERMISSION_SEE_DOWNLOAD->value)
        ->name('documment.management.folders.permission.see_download');

    Route::post('/document_management/folder_permissions_create/{folder_area_id}', [FolderController::class, 'create_permission'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_PERMISSION_CREATE->value)
        ->name('documment.management.folders.permission.create');

    Route::post('/document_management/folder_permissions_add', [FolderController::class, 'folder_permission_add'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_PERMISSION_ADD->value)
        ->name('documment.management.folders.permission.add');

    Route::delete('/document_management/folder_permissions_delete/{folder_area_id}', [FolderController::class, 'folder_permission_remove'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_PERMISSION_DELETE->value)
        ->name('documment.management.folders.permission.delete');

    Route::delete('document_management/folder_destroy/{folder_id}', [FolderController::class, 'folder_delete'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_DELETE->value)
        ->name('document.management.folder.destroy');

    Route::get('/documment_management/folder_permission/{folder_id}', [FolderController::class, 'folder_permissions'])
        ->middleware('permission:' .UserPermissions::DOCUMENT_FOLDERS_PERMISSION_VIEW->value)
        ->name('documment.management.folders.permissions');

    // Inventory
    Route::put('/inventory/purchase_products/products/{purchase_product}/disable', [PurchaseProductsController::class, 'disable'])
        ->middleware('permission:' .UserPermissions::INVENTORY_PURCHASE_PRODUCTS_DISABLE->value)
        ->name('inventory.purchaseproducts.disable');

    // Inventory Product Type and Active Type
    Route::post('/inventory/purchase_products/type_product/store', [PurchaseProductsController::class, 'typeProducts'])
        ->middleware('permission:' .UserPermissions::INVENTORY_PURCHASE_PRODUCTS_TYPE_STORE->value)
        ->name('inventory.purchaseproducts.typeProduct');

    Route::post('/inventory/purchase_products/resource_type/store', [PurchaseProductsController::class, 'resourceType'])
        ->middleware('permission:' .UserPermissions::INVENTORY_PURCHASE_PRODUCTS_RESOURCE_TYPE_STORE->value)
        ->name('inventory.purchaseproducts.resourceType');

    // Resource
    Route::get('/inventory/resource/purchase_orders/create', [WarehousesController::class, 'resource_create'])
        ->middleware('permission:' .UserPermissions::WAREHOUSES_RESOURCE_CREATE->value)
        ->name('warehouses.resource.create');

    Route::post('/inventory/resource/purchase_orders/store', [WarehousesController::class, 'resource_store'])
        ->middleware('permission:' .UserPermissions::WAREHOUSES_RESOURCE_STORE->value)
        ->name('warehouses.resource.store');

    // Providers
    Route::get('/shopping_area/providers/edit/{id}', [ProviderController::class, 'edit'])
        ->middleware('permission:' .UserPermissions::PROVIDERS_EDIT->value)
        ->name('providersmanagement.edit');

    Route::post('/shopping_area/providers/update/{id}', [ProviderController::class, 'update'])
        ->middleware('permission:' .UserPermissions::PROVIDERS_UPDATE->value)
        ->name('providersmanagement.update');

    Route::delete('/shopping_area/providers/destroy/{id}', [ProviderController::class, 'destroy'])
        ->middleware('permission:' .UserPermissions::PROVIDERS_DESTROY->value)
        ->name('providersmanagement.destroy');

    // Purchase Request
    Route::get('/shopping_area/purchasesrequest/edit/{id}/{project_id?}', [PurchaseRequestController::class, 'edit'])
        ->middleware('permission:' .UserPermissions::PURCHASESREQUEST_EDIT->value)
        ->name('purchasesrequest.edit');

    Route::put('/shopping_area/purchasesrequest/update/{id}', [PurchaseRequestController::class, 'update'])
        ->middleware('permission:' .UserPermissions::PURCHASESREQUEST_UPDATE->value)
        ->name('purchasesrequest.update');

});


Route::middleware('permission:UserManager|UserGestionManager|UserGestion')->group(function () {
    Route::get('users', [UserController::class, 'index_user'])
        ->middleware('permission:' .UserPermissions::USERS_INDEX->value)
        ->name('users.index');

    Route::post('users/search', [UserController::class, 'search'])
        ->middleware('permission:' .UserPermissions::USERS_SEARCH->value)
        ->name('users.search');

    Route::get('users/details/{id}', [UserController::class, 'details'])
        ->middleware('permission:' .UserPermissions::USERS_DETAILS->value)
        ->name('users.details');

});