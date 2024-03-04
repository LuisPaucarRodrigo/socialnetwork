<?php

use App\Http\Controllers\Finance\DepositController;
use App\Http\Controllers\Finance\ExpenseManagementController;
use App\Http\Controllers\HttpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ManagementRolsController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use App\Http\Controllers\ProjectArea\CalendarController;
use App\Http\Controllers\Finance\BudgetUpdateController;
use App\Http\Controllers\Finance\GangExpenseController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\LiquidationController;
use App\Http\Controllers\ProjectArea\ProjectScheduleController;
use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\ProjectReportsController;
use App\Http\Controllers\ProjectArea\PreProjectController;
use App\Http\Controllers\ShoppingArea\PurchaseRequestController;
use App\Http\Controllers\ShoppingArea\ProviderController;
use App\Http\Controllers\ShoppingArea\PurchaseOrdersController;
use App\Http\Controllers\HumanResource\FormationDevelopment;
use App\Http\Controllers\ProjectArea\TaskManagementController;
use App\Http\Controllers\HumanResource\VacationController;
use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\SectionController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\Inventory\ResourceManagementController;
use App\Http\Controllers\Inventory\InventoryControlController;
use App\Http\Controllers\Inventory\WarehousesController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\ProjectArea\CustomerVisitController;
use App\Http\Controllers\ShoppingArea\PaymentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

// use App\Http\Controllers\VacationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
         
Route::middleware('auth', 'permission:UserManager')->group(function () {

    Route::get('users', [UserController::class, 'index_user'])->name('users.index');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('users/details/{id}', [UserController::class, 'details'])->name('users.details');
    Route::delete('users/delete/{id}', [UserController::class, 'delete'])->name('users.destroy');

    Route::get('rols', [ManagementRolsController::class, 'rols_index'])->name('rols.index');
    Route::get('rols/create', [ManagementRolsController::class, 'create'])->name('rols.create');
    Route::post('rols/store', [ManagementRolsController::class, 'store'])->name('rols.store');
    Route::delete('rols/delete/{id}', [ManagementRolsController::class, 'delete'])->name('rols.delete');
    Route::get('rols/details/{id}', [ManagementRolsController::class, 'details'])->name('rols.details');


    //FINANZAS
    Route::put('/finance/expensegang/{id}/update', [GangExpenseController::class, 'update'])->name('gangexpense.update');



    //PROYECTOS
    //preprojects
    Route::post('/preprojects/{preproject}/update', [PreProjectController::class, 'update'])->name('preprojects.update');
    Route::delete('/preprojects/{preproject}/destroy', [PreProjectController::class, 'destroy'])->name('preprojects.destroy');
    Route::post('/preprojects/photoreport_update/{photoreport}', [PreProjectController::class, 'photoreport_update'])->name('preprojects.photoreport.update');
    Route::delete('/preprojects/photoreport_delete/{photoreport}', [PreProjectController::class, 'photoreport_delete'])->name('preprojects.photoreport.delete');
    Route::delete('/preprojects/providers_quotes/delete/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_delete'])->name('preprojects.providersquotes.delete');
    Route::delete('/preprojects/quote_delete/{quote_item_id}', [PreProjectController::class, 'quote_item_delete'])->name('preprojects.quote.item.delete');
    Route::post('/preprojects/quote_item_store', [PreProjectController::class, 'quote_item_store'])->name('preprojects.quote.item.store');
    //projects
    Route::get('/projectmanagement/update/{project_id}', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.update');
    Route::delete('/projectmanagement/delete/{project_id}', [ProjectManagementController::class, 'project_destroy'])->name('projectmanagement.delete');
    Route::post('/projectmanagement/resources/return/{id}', [ProjectManagementController::class, 'project_resources_return'])->name('projectmanagement.resources.return');
    Route::delete('/shopping_area/purchasesrequest/destroy/{id}', [PurchaseRequestController::class, 'destroy'])->name('purchasesrequest.destroy');
    Route::put('/projectmanagement/purchases_request/{project_id}/additional_costs/{additional_cost}/update', [AdditionalCostsController::class, 'update'])->name('projectmanagement.updateAdditionalCost');
    Route::delete('/projectmanagement/purchases_request/{project_id}/additional_costs/{additional_cost}/destroy', [AdditionalCostsController::class, 'destroy'])->name('projectmanagement.deleteAdditionalCost');
    //Tareas
    Route::post('/edittask/delete', [TaskManagementController::class, 'delete_employee'])->name('tasks.delete.employee');
    Route::delete('/deletetask/{taskId}/', [TaskManagementController::class, 'delete_task'])->name('tasks.delete');
    //Cicsa subsection
    Route::put('/cicsaSubSections/{subSection}/update', [CicsaSectionController::class, 'updateSubSection'])->name('sections.cicsaUpdateSubSection');
    Route::delete('/cicsaSubSections/{subSection}/delete', [CicsaSectionController::class, 'destroySubSection'])->name('sections.cicsaDestroySubSection');
    Route::delete('/cicsaSections/{section}', [CicsaSectionController::class, 'destroySection'])->name('sections.cicsaDestroySection');


    //INVENTARIO
    //activos
    Route::post('resource_description/store', [ResourceManagementController::class,'resource_description_store'])->name('resource_description.store');
    Route::post('resource_category/store', [ResourceManagementController::class,'resource_category_store'])->name('resource_category.store');

    //HumanResource
    Route::get('/management_employees/edit/{id}', [ManagementEmployees::class, 'edit'])->name('management.employees.edit');
    Route::post('/management_employees/update/{id}', [ManagementEmployees::class, 'update'])->name('management.employees.update');
    Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])->name('management.employees.destroy');
    Route::put('/management_employees/fired/{id}', [ManagementEmployees::class, 'fired'])->name('management.employees.fired');
    Route::put('/management_employees/{id}/reentry', [ManagementEmployees::class, 'reentry'])->name('management.employees.reentry');

    //Formation Development program
    Route::get('/management_employees/formation_development/view/{id}', [FormationDevelopment::class, 'formation_programs_view'])->name('management.employees.formation_development.view');
    Route::delete('/management_employees/formation_development/delete/{id}', [FormationDevelopment::class, 'formation_programs_destroy'])->name('management.employees.formation_development.delete');
    Route::post('/management_employees/formation_development/delete-employee/', [FormationDevelopment::class, 'formation_programs_destroy_employee'])->name('management.employees.formation_development.employee.delete');

    //Training
    Route::delete('/management_employees/formation_development/trainings/delete/{id}', [FormationDevelopment::class, 'trainings_destroy'])->name('management.employees.formation_development.trainings.destroy');


    //Vacation
    Route::get('/management_vacation/information_additional/{vacation}', [VacationController::class, 'edit'])->name('management.vacation.information.edit');
    Route::put('/management_vacation/information_additional/{vacation}/update', [VacationController::class, 'update'])->name('management.vacation.information.update');
    Route::get('/management_vacation/information_additional/{vacation}/review', [VacationController::class, 'review'])->name('management.vacation.information.review');
    Route::get('/management_vacation/information_additional/{id}/reviewed', [VacationController::class, 'reviewed'])->name('management.vacation.information.reviewed');
    Route::get('/management_vacation/information_additional/{id}/decline', [VacationController::class, 'decline'])->name('management.vacation.information.decline');
    Route::delete('/management_vacation/information_additional/{vacation}/delete', [VacationController::class, 'destroy'])->name('management.vacation.information.destroy');

    //Document
    Route::delete('/documents/{id}/delete', [DocumentController::class, 'destroy'])->name('documents.destroy');

    //SubSectionAlarmRRHH
    Route::put('/subSections/{subSection}/update', [SectionController::class, 'updateSubSection'])->name('sections.updateSubSection');
    Route::delete('/subSections/{subSection}/delete', [SectionController::class, 'destroySubSection'])->name('sections.destroySubSection');


});

Route::middleware('auth', 'permission:HumanResourceManager')->group(function () {
    Route::get('/management_employees/index/{reentry?}', [ManagementEmployees::class, 'index'])->name('management.employees');
    Route::get('/management_employees/information_additional', [ManagementEmployees::class, 'index_info_additional'])->name('management.employees.information');
    Route::post('/management_employees/information_additional/create', [ManagementEmployees::class, 'create'])->name('management.employees.information.create');
    Route::get('/management_employees/information_additional/details/{id}', [ManagementEmployees::class, 'details'])->name('management.employees.information.details');
    Route::get('/management_employees/information_additional/details/download/{id}', [ManagementEmployees::class, 'download'])->name('management.employees.information.details.download');

    // Route::get('/management_employees/edit/{id}', [ManagementEmployees::class, 'edit'])->name('management.employees.edit');
    // Route::post('/management_employees/update/{id}', [ManagementEmployees::class, 'update'])->name('management.employees.update');
    // Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])->name('management.employees.destroy');
    // Route::put('/management_employees/fired/{id}', [ManagementEmployees::class, 'fired'])->name('management.employees.fired');
    // Route::put('/management_employees/{id}/reentry', [ManagementEmployees::class, 'reentry'])->name('management.employees.reentry');

    
    //Schedule
    Route::post('/management_employees/addSchedule', [ManagementEmployees::class, 'uploadSchedule'])->name('management.employees.addSchedule');
    Route::post('/management_employees/updateSchedule', [ManagementEmployees::class, 'updateSchedule'])->name('management.employees.updateSchedule');
    //Nomina
    Route::get('/management_employees/spreadsheets/{reentry?}', [SpreadsheetsController::class, 'index'])->name('spreadsheets.index');
    Route::get('/management_employees/pension_system/edit', [SpreadsheetsController::class, 'edit'])->name('pension_system.edit');
    Route::put('/management_employees/pension_system/update/{id}', [SpreadsheetsController::class, 'update'])->name('pension_system.update');
    Route::put('/management_employees/pension_system/update_seg/{id}', [SpreadsheetsController::class, 'update_seg'])->name('pension_system_seg.update');

    Route::get('/management_employees/spreadsheets/payroll/export', [SpreadsheetsController::class, 'export'])->name('spreadsheets.payroll.export');


    //Formation Development program
    Route::get('/management_employees/formation_development', [FormationDevelopment::class, 'index'])->name('management.employees.formation_development');
    Route::get('/management_employees/formation_development/assignation-create', [FormationDevelopment::class, 'assignate_create'])->name('management.employees.formation_development.assignation.create');
    Route::post('/management_employees/formation_development/assignation-store', [FormationDevelopment::class, 'assignate_store'])->name('management.employees.formation_development.assignation.store');

    // Route::get('/management_employees/formation_development/view/{id}', [FormationDevelopment::class, 'formation_programs_view'])->name('management.employees.formation_development.view');
    // Route::delete('/management_employees/formation_development/delete/{id}', [FormationDevelopment::class, 'formation_programs_destroy'])->name('management.employees.formation_development.delete');
    // Route::post('/management_employees/formation_development/delete-employee/', [FormationDevelopment::class, 'formation_programs_destroy_employee'])->name('management.employees.formation_development.employee.delete');

    //Formation Programas
    Route::get('/management_employees/formation_development/formation_programs', [FormationDevelopment::class, 'formation_programs_index'])->name('management.employees.formation_development.formation_programs');
    Route::get('/management_employees/formation_development/formation_programs/create', [FormationDevelopment::class, 'formation_programs_create'])->name('management.employees.formation_development.formation_programs.create');
    Route::post('/management_employees/formation_development/formation_programs/store', [FormationDevelopment::class, 'formation_programs_store'])->name('management.employees.formation_development.formation_programs.store');

    //Trainings
    Route::get('/management_employees/formation_development/trainings', [FormationDevelopment::class, 'trainings_index'])->name('management.employees.formation_development.trainings');
    Route::get('/management_employees/formation_development/trainings/create/{id?}', [FormationDevelopment::class, 'trainings_create'])->name('management.employees.formation_development.trainings.create');
    Route::post('/management_employees/formation_development/trainings/store/{id?}', [FormationDevelopment::class, 'trainings_store'])->name('management.employees.formation_development.trainings.store');
    // Route::delete('/management_employees/formation_development/trainings/delete/{id}', [FormationDevelopment::class, 'trainings_destroy'])->name('management.employees.formation_development.trainings.destroy');

    
    //Employees in programs
    Route::get('/management_employees/formation_development/employees_in_programs', [FormationDevelopment::class,'employees_in_programs'])->name('management.employees.formation_development.employees_in_programs');


    //Vacation
    Route::get('/management_vacation', [VacationController::class, 'index'])->name('management.vacation');
    Route::get('/management_vacation/information_additional', [VacationController::class, 'create'])->name('management.vacation.information.create');
    Route::post('/management_vacation/information_additional/store', [VacationController::class, 'store'])->name('management.vacation.information.store');
    // Route::get('/management_vacation/information_additional/{vacation}', [VacationController::class, 'edit'])->name('management.vacation.information.edit');
    // Route::put('/management_vacation/information_additional/{vacation}/update', [VacationController::class, 'update'])->name('management.vacation.information.update');
    // Route::get('/management_vacation/information_additional/{vacation}/review', [VacationController::class, 'review'])->name('management.vacation.information.review');
    // Route::get('/management_vacation/information_additional/{id}/reviewed', [VacationController::class, 'reviewed'])->name('management.vacation.information.reviewed');
    Route::get('/management_vacation/information_additional/{vacation}/details', [VacationController::class, 'details'])->name('management.vacation.information.details');
    Route::get('/management_vacation/information_additional/{id}/showDocument', [VacationController::class, 'showDocument'])->name('management.vacation.information.documents.show');
    // Route::get('/management_vacation/information_additional/{id}/decline', [VacationController::class, 'decline'])->name('management.vacation.information.decline');
    // Route::delete('/management_vacation/information_additional/{vacation}/delete', [VacationController::class, 'destroy'])->name('management.vacation.information.destroy');

    //Document
    Route::get('/documents/index', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents/store', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents/update/{id}', [DocumentController::class, 'update'])->name('documents.update');
    Route::get('/documents/{document}/download', [DocumentController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'showDocument'])->name('documents.show');
    // Route::delete('/documents/{id}/delete', [DocumentController::class, 'destroy'])->name('documents.destroy');

    //DocumentSections
    Route::get('/document_sections', [DocumentController::class, 'showSections'])->name('documents.sections');
    Route::post('/document_sections', [DocumentController::class, 'storeSection'])->name('documents.storeSection');
    Route::delete('/document_sections/{section}', [DocumentController::class, 'destroySection'])->name('documents.destroySection');

    //Subdivisions
    Route::get('/document_sections/{section}/subdivisions', [DocumentController::class, 'showSubdivisions'])->name('documents.subdivisions');
    Route::post('/document_sections/{section}/subdivisions', [DocumentController::class, 'storeSubdivision'])->name('documents.storeSubdivision');
    Route::delete('/document_sections/{section}/subdivisions/{subdivision}', [DocumentController::class, 'destroySubdivision'])->name('documents.destroySubdivision');

    //Sections
    Route::get('/sections', [SectionController::class, 'showSections'])->name('sections.sections');
    Route::post('/sections', [SectionController::class, 'storeSection'])->name('sections.storeSection');
    Route::delete('/sections/{section}', [SectionController::class, 'destroySection'])->name('sections.destroySection');

    //SubSections
    Route::get('/subSections', [SectionController::class, 'showSubSections'])->name('sections.subSections');
    Route::get('/subSections/{subSection}', [SectionController::class, 'showSubSection'])->name('sections.subSection');
    Route::post('/subSections', [SectionController::class, 'storeSubSection'])->name('sections.storeSubSection');
    // Route::put('/subSections/{subSection}/update', [SectionController::class, 'updateSubSection'])->name('sections.updateSubSection');
    // Route::delete('/subSections/{subSection}/delete', [SectionController::class, 'destroySubSection'])->name('sections.destroySubSection');
    Route::get('/doTask', [SectionController::class, 'doTask'])->name('sections.task');
    Route::get('/doTask2', [SectionController::class, 'doTask2'])->name('sections.task2');
});

Route::middleware('auth', 'permission:FinanceManager')->group(function () {

    //Expense
    Route::get('/finance/expensegang', [GangExpenseController::class, 'index'])->name('gangexpense.index');
    Route::get('/finance/expensegang/create', [GangExpenseController::class, 'create'])->name('gangexpense.create');
    Route::post('/finance/expensegang/store', [GangExpenseController::class, 'store'])->name('gangexpense.store');
    Route::get('/finance/expensegang/{id}/edit', [GangExpenseController::class, 'edit'])->name('gangexpense.edit');
    Route::post('/finance/expensegang/search', [GangExpenseController::class, 'search'])->name('gangexpense.search');

    Route::get('/finance/expencemanagement', [ExpenseManagementController::class, 'index'])->name('managementexpense.index');
    Route::get('/finance/expencemanagement/details/{purchase_quote}', [ExpenseManagementController::class, 'details'])->name('managementexpense.details');

    Route::put('/finance/expencemanagement/reviewed/{id}', [ExpenseManagementController::class, 'reviewed'])->name('managementexpense.reviewed');
    Route::get('/finance/expencemanagement/generate_payment/{id}', [ExpenseManagementController::class, 'generate_payment'])->name('managementexpense.payment');
    Route::get('/finance/purchase_quotes/doTask', [ExpenseManagementController::class, 'doTask'])->name('finance.task');
    //Budget
    Route::get('/budgetUpdates/{project}', [BudgetUpdateController::class, 'index'])->name('budgetupdates.index');
    Route::get('/selectProject', [BudgetUpdateController::class, 'selectProject'])->name('selectproject.index');
    Route::get('/budgetUpdates/{project}/{budgetupdate}', [BudgetUpdateController::class, 'show'])->name('budgetupdates.show');
    Route::get('/initialBudget/{project}', [BudgetUpdateController::class, 'initial'])->name('initialbudget.index');
    Route::post('/initialBudget/{project}/createUpdate', [BudgetUpdateController::class, 'create'])->name('budgetupdates.create');
    





    Route::get('/finance/desposits', [DepositController::class,'deposits_index'])->name('deposits.index');
    Route::post('/finance/desposits/{deposit_id?}', [DepositController::class,'deposits_store'])->name('deposits.store');
    //
    Route::post('/finance/desposits/generateSummary/post', [DepositController::class,'generateSummary'])->name('deposits.generateSummary');

});

Route::middleware('auth', 'permission:InventoryManager')->group(function () {
    
    //Resources
    Route::get('/resources', [ResourceManagementController::class, 'index'])->name('resources.index');
    Route::get('/resources/new', [ResourceManagementController::class, 'new'])->name('resources.new');
    Route::get('/resources/details/{id}', [ResourceManagementController::class, 'details'])->name('resources.details');
    Route::post('/resources/create', [ResourceManagementController::class, 'create'])->name('resource.create');
    Route::get('/resources/edit/{resourceId}', [ResourceManagementController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/edit/{resourceId}', [ResourceManagementController::class, 'update'])->name('resource.update');
    Route::delete('/resources/delete/{resourceId}', [ResourceManagementController::class, 'destroy'])->name('resource.delete');

    Route::post('resource_description/store', [ResourceManagementController::class,'resource_description_store'])->name('resource_description.store');
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
    


});

Route::middleware('auth', 'permission:ProjectManager')->group(function () {
    Route::get('/projectmanagement', [ProjectManagementController::class, 'index'])->name('projectmanagement.index');
    Route::get('/projectmanagement/create', [ProjectManagementController::class, 'project_create'])->name('projectmanagement.create');
    Route::post('/projectmanagement/store', [ProjectManagementController::class, 'project_store'])->name('projectmanagement.store');
    Route::post('/projectmanagement/update/{project_id}/add-employee', [ProjectManagementController::class, 'project_add_employee'])->name('projectmanagement.add.employee');
    Route::delete('/projectmanagement/update/delete-employee/{pivot_id}', [ProjectManagementController::class, 'project_delete_employee'])->name('projectmanagement.delete.employee');

    Route::get('/projectmanagement/resources/{project_id}', [ProjectManagementController::class, 'project_resources'])->name('projectmanagement.resources');
    Route::post('/projectmanagement/resources', [ProjectManagementController::class, 'project_resources_store'])->name('projectmanagement.resources.store');
    Route::post('/projectmanagement/resources/liquidate', [ProjectManagementController::class,'project_resources_liquidate'])->name('projectmanagement.resourcesLiquidate');

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
    Route::get('/preprojects', [PreProjectController::class, 'index'])->name('preprojects.index');
    Route::post('/preprojects/create', [PreProjectController::class, 'store'])->name('preprojects.store');
    Route::get('/preprojects/{preproject}/facade', [PreProjectController::class, 'showPreprojectFacade'])->name('preprojects.facade');
    Route::get('/cotizationPDF/{preproject}', [PreProjectController::class, 'getPDF'])->name('preprojects.pdf');

    Route::get('/preprojects/{preproject_id}/quote', [PreProjectController::class, 'quote'])->name('preprojects.quote');
    Route::post('/preprojects/quote_store/{quote_id?}', [PreProjectController::class, 'quote_store'])->name('preprojects.quote.store');
    Route::post('/preprojects/quote/{quote_id}', [PreProjectController::class, 'acceptCotization'])->name('preprojects.accept');
    
    

    Route::get('/preprojects/{preproject_id}/photoreport', [PreProjectController::class, 'photoreport_index'])->name('preprojects.photoreport.index');
    Route::post('/preprojects/photoreport_store', [PreProjectController::class, 'photoreport_store'])->name('preprojects.photoreport.store');
    Route::get('/preprojects/photoreport_download/{report_name}', [PreProjectController::class, 'downloadPR'])->name('preprojects.photoreport.download');
    Route::get('/preprojects/photoreport_show/{report_name}', [PreProjectController::class, 'showPR'])->name('preprojects.photoreport.show');
    Route::get('/preprojects/quote_pdf/{quote_id}', [PreProjectController::class, 'quote_pdf'])->name('preprojects.quote.pdf');


    Route::get('/preprojects/{preproject_id}/providers_quotes', [PreProjectController::class, 'preproject_providersquotes_index'])->name('preprojects.providersquotes.index');
    Route::post('/preprojects/providers_quotes/store', [PreProjectController::class, 'preproject_providersquotes_store'])->name('preprojects.providersquotes.store');
    Route::get('/preprojects/providers_quotes/show/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_show'])->name('preprojects.providersquotes.show');
    Route::get('/preprojects/providers_quotes/download/{providerquote_id}', [PreProjectController::class, 'preproject_providersquotes_download'])->name('preprojects.providersquotes.download');


    //test api
    Route::get('/sunat_ruc', [HttpController::class,'sunat_ruc'])->name('sunat');



    Route::get('/preprojects/{preproject_id}/report/image', [PreProjectController::class, 'index_image'])->name('preprojects.imagereport.index');
    Route::get('/preprojects/{preproject_id}/report/download_image', [PreProjectController::class, 'download_image'])->name('preprojects.imagereport.download');
    Route::get('/preprojects/{image}/report/showimage', [PreProjectController::class, 'show_image'])->name('preprojects.imagereport.show');
    Route::delete('/preprojects/{preproject_id}/report/delete', [PreProjectController::class, 'delete_image'])->name('preprojects.imagereport.delete');


    Route::get('/projectmanagement/purchases_request/{project_id}', [ProjectManagementController::class, 'project_purchases_request_index'])->name('projectmanagement.purchases_request.index');
    Route::get('/projectmanagement/purchases_request/{project_id}/create/{purchase_id?}', [ProjectManagementController::class, 'project_purchases_request_create'])->name('projectmanagement.purchases_request.create');
    Route::post('/projectmanagement/purchases_request/{project_id}/store', [ProjectManagementController::class, 'project_purchases_request_store'])->name('projectmanagement.purchases_request.store');
    Route::get('/projectmanagement/expenses/{project_id}', [ProjectManagementController::class, 'project_expenses'])->name('projectmanagement.expenses');

    //additional_cost
    Route::get('/projectmanagement/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'index'])->name('projectmanagement.additionalCosts');
    Route::post('/projectmanagement/purchases_request/{project_id}/additional_costs', [AdditionalCostsController::class, 'store'])->name('projectmanagement.storeAdditionalCost');
    

    Route::get('/projectmanagement/products/{project_id}', [ProjectManagementController::class, 'project_product_index'])->name('projectmanagement.products');
    Route::post('/projectmanagement/products/{project_id}/liquidate', [LiquidationController::class, 'store'])->name('projectmanagement.productsLiquidate');
    Route::get('/projectmanagement/products/{project_id}/liquidateTable', [LiquidationController::class, 'index'])->name('projectmanagement.liquidate');

    Route::get('/projectmanagement/warehouse_products/{warehouse_id}', [ProjectManagementController::class, 'warehouse_products'])->name('projectmanagement.warehouse_products');
    Route::post('/projectmanagement/products/store', [ProjectManagementController::class, 'project_product_store'])->name('projectmanagement.products.store');
    Route::put('/projectmanagement/products/update/{project_product}', [ProjectManagementController::class, 'project_product_update'])->name('projectmanagement.products.update');
    Route::delete('/projectmanagement/warehouse_products/{assigned}', [ProjectManagementController::class, 'warehouse_products_delete'])->name('projectmanagement.products.delete');



    //Tasks Management
    Route::get('/tasks/{id?}', [TaskManagementController::class, 'index'])->name('tasks.index');
    Route::get('/newtask', [TaskManagementController::class, 'new'])->name('tasks.new');
    Route::post('/createtask', [TaskManagementController::class, 'create'])->name('tasks.create');
    Route::get('/edittask/{taskId}', [TaskManagementController::class, 'edit'])->name('tasks.edit');
    Route::post('/edittask/comment', [TaskManagementController::class, 'comment'])->name('tasks.edit.comment');
    Route::post('/edittask/add', [TaskManagementController::class, 'add_employee'])->name('tasks.add.employee');
    Route::get('/statustask/{taskId}/{status}', [TaskManagementController::class, 'status_task'])->name('tasks.edit.status');
    //Calendar
    Route::get('/calendarProjects', [CalendarController::class, 'index'])->name('projectscalendar.index');
    Route::get('/calendarTasks/{project}', [CalendarController::class, 'show'])->name('projectscalendar.show');
});

Route::middleware('auth', 'permission:PurchasingManager')->group(function () {
    Route::get('/shopping_area/purchasesrequest', [PurchaseRequestController::class, 'index'])->name('purchasesrequest.index');
    Route::get('/shopping_area/purchasesrequest/create_request', [PurchaseRequestController::class, 'create'])->name('purchasesrequest.create');
    Route::post('/shopping_area/purchasesrequest/store_request', [PurchaseRequestController::class, 'store'])->name('purchasesrequest.store');
    Route::get('/shopping_area/purchasesrequest/edit/{id}', [PurchaseRequestController::class, 'edit'])->name('purchasesrequest.edit');
    Route::put('/shopping_area/purchasesrequest/update/{id}', [PurchaseRequestController::class, 'update'])->name('purchasesrequest.update');
    Route::get('/shopping_area/purchasesrequest/quotes/{id}', [PurchaseRequestController::class, 'index_quotes'])->name('purchasesrequest.quotes');
    Route::get('/shopping_area/purchasesrequest/quotes/{id}/preview', [PurchaseRequestController::class, 'showDocument'])->name('purchasesrequest.show');
    Route::get('/shopping_area/purchasesrequest/details/{id}', [PurchaseRequestController::class, 'details'])->name('purchasingrequest.details');
    Route::post('/shopping_area/purchasesrequest/orders', [PurchaseRequestController::class, 'quote'])->name('purchasesrequest.storequotes');
    Route::post('/shopping_area/purchasesrequest/reject/{id}', [PurchaseRequestController::class, 'reject_request'])->name('purchasesrequest.reject_request');
    Route::get('/shopping_area/purchasesrequest/doTask', [PurchaseRequestController::class, 'doTask'])->name('purchasesrequest.task');

    Route::get('/shopping_area/purchaseorders', [PurchaseOrdersController::class, 'index'])->name('purchaseorders.index');
    Route::get('/shopping_area/purchaseorders_details/{purchase_order_id}', [PurchaseOrdersController::class, 'purchase_order_view'])->name('purchaseorders.details');
    Route::get('/shopping_area/purchaseorders/history', [PurchaseOrdersController::class, 'history'])->name('purchaseorders.history');
    Route::put('/shopping_area/purchaseorders/state/{id}', [PurchaseOrdersController::class, 'state'])->name('purchaseorders.state');
    Route::get('/shopping_area/alarms', [PurchaseOrdersController::class, 'purchase_orders_alarms'])->name('purchaseorders.alarms');
    Route::get('/shopping_area/purchaseorders/{id}/export', [PurchaseOrdersController::class, 'purchase_orders_export'])->name('purchaseorders.export.order');


    Route::get('/shopping_area/providers', [ProviderController::class, 'index'])->name('providersmanagement.index');
    Route::get('/shopping_area/providers/create', [ProviderController::class, 'create'])->name('providersmanagement.create');
    Route::post('/shopping_area/providers/store', [ProviderController::class, 'store'])->name('providersmanagement.store');
    Route::get('/shopping_area/providers/edit/{id}', [ProviderController::class, 'edit'])->name('providersmanagement.edit');
    Route::put('/shopping_area/providers/update/{id}', [ProviderController::class, 'update'])->name('providersmanagement.update');
    Route::delete('/shopping_area/providers/destroy/{id}', [ProviderController::class, 'destroy'])->name('providersmanagement.destroy');

    Route::post('/shopping_area/providers/category', [ProviderController::class, 'category_provider'])->name('provider.category');
    Route::post('/shopping_area/providers/segment', [ProviderController::class, 'segment_provider'])->name('provider.segment');

    Route::get('/shopping_area/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::put('/shopping_area/payment/$id/pay', [PaymentController::class, 'payment_pay'])->name('payment.pay');


});

require __DIR__ . '/auth.php';
