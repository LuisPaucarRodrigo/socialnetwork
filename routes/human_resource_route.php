<?php

use App\Constants\RolesConstants;
use App\Http\Controllers\HumanResource\DocumentSpreedSheetController;
use App\Http\Controllers\HumanResource\ControlEmployees;
use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\FormationDevelopment;
use App\Http\Controllers\HumanResource\GrupalDocumentController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\ScheduleController;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use App\Http\Controllers\HumanResource\VacationController;
use Illuminate\Support\Facades\Route;
use App\Enums\Permissions\HumanResourcesPermissions;

//new permission routes

// Human Resource
Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])
    ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_DELETE->value)
    ->name('management.employees.destroy');

Route::put('/management_employees/fired/{id}', [ManagementEmployees::class, 'fired'])
    ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_FIRED->value)
    ->name('management.employees.fired');

Route::put('/management_employees/{id}/reentry', [ManagementEmployees::class, 'reentry'])
    ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_REENTRY->value)
    ->name('management.employees.reentry');

// Formation Development Program
Route::delete('/management_employees/formation_development/delete/{id}', [FormationDevelopment::class, 'formation_programs_destroy'])
    ->middleware('permission:' . HumanResourcesPermissions::FORMATION_DEVELOPMENT_DELETE->value)
    ->name('management.employees.formation_development.delete');

Route::delete('/management_employees/formation_development/delete-employee/{efp_id}', [FormationDevelopment::class, 'formation_programs_destroy_employee'])
    ->middleware('permission:' . HumanResourcesPermissions::FORMATION_DEVELOPMENT_EMPLOYEE_DELETE->value)
    ->name('management.employees.formation_development.employee.delete');

// Training
Route::delete('/management_employees/formation_development/trainings/delete/{id}', [FormationDevelopment::class, 'trainings_destroy'])
    ->middleware('permission:' . HumanResourcesPermissions::TRAININGS_DELETE->value)
    ->name('management.employees.formation_development.trainings.destroy');

// Vacation
Route::get('/management_vacation/information_additional/{vacation}/review', [VacationController::class, 'review'])
    ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_VACATION_REVIEW->value)
    ->name('management.vacation.information.review');

Route::post('/management_vacation/information_additional/reviewed/decline', [VacationController::class, 'reviewed_and_decline'])
    ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_VACATION_REVIEW_DECLINE->value)
    ->name('management.vacation.information.reviewed_decline');

Route::delete('/management_vacation/information_additional/{vacation}/delete', [VacationController::class, 'destroy'])
    ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_VACATION_DELETE->value)
    ->name('management.vacation.information.destroy');




// Document
Route::delete('/documents/{id}/delete', [DocumentController::class, 'destroy'])
    ->middleware('permission:' . HumanResourcesPermissions::DOCUMENT_DELETE->value)
    ->name('documents.destroy');





Route::middleware('permission:' . implode('|', RolesConstants::HR_MODULE))->group(function () {
    Route::get('/management_employees/information_additional', [ManagementEmployees::class, 'create'])
        ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_CREATE->value)
        ->name('management.employees.create');
    Route::post('/management_employees/information_additional/create', [ManagementEmployees::class, 'store'])->name('management.employees.store');
    Route::get('/management_employees/edit/{id}', [ManagementEmployees::class, 'edit'])
        ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_EDIT->value)
        ->name('management.employees.edit');
    Route::post('/management_employees/update/{id}', [ManagementEmployees::class, 'update'])->name('management.employees.update');

    //Empleados Externos
    Route::get('/management_employees/external/index', [ManagementEmployees::class, 'external_index'])
        ->middleware('permission:' . HumanResourcesPermissions::EMPLOYEES_EXTERNAL_INDEX->value)
        ->name('employees.external.index');
    Route::post('/management_employees/external/search', [ManagementEmployees::class, 'external_search'])->name('employees.external.search');
    Route::post('/management_employees/storeorupdate/{external_id?}', [ManagementEmployees::class, 'storeorupdate'])
        ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EXTERNAL_STOREORUPDATE->value)
        ->name('management.external.storeorupdate');
    Route::delete('/management_employees/external/delete/{id}', [ManagementEmployees::class, 'external_delete'])->name('employees.external.delete');

    Route::get('/management_employees/external/preview/{external_preview_id}/curriculum_vitae', [ManagementEmployees::class, 'preview_curriculum_vitae'])->name('employees.external.preview.curriculum_vitae');

    //Control of Employees
    Route::any('/management_employees/control_employees/index', [ControlEmployees::class, 'control_employees_index'])->name('controlEmployees.index');

    Route::any('/management_employees/fixed_documentation/index', [ControlEmployees::class, 'fixed_documentation_index'])->name('controlEmployees.fixed.documentation.index');
    Route::post('/management_employees/fixed_documentation/storeOrUpdate/{fixed_documentation_id?}', [ControlEmployees::class, 'fixed_documentation_storeOrUpdate'])->name('controlEmployees.fixed.documentation.store');

    Route::any('/management_employees/entry_documentation/index', [ControlEmployees::class, 'entry_document_index'])->name('controlEmployees.entry.document.index');
    Route::post('/management_employees/entry_documentation/storeOrUpdate/{fixed_documentation_id?}', [ControlEmployees::class, 'entry_document_storeOrUpdate'])->name('controlEmployees.entry.document.storeOrUpdate');

    Route::any('/management_employees/issuance_documentation/index', [ControlEmployees::class, 'issuance_documentation_index'])->name('controlEmployees.issuance.documentation.index');
    Route::post('/management_employees/issuance_documentation/storeOrUpdate/{fixed_documentation_id?}', [ControlEmployees::class, 'issuance_documentation_storeOrUpdate'])->name('controlEmployees.issuance.documentation.storeOrUpdate');

    //Schedule    
    Route::get('/management_employees/schedule/index', [ScheduleController::class, 'index'])->name('management.employees.schedule.index');
    Route::post('/management_employees/schedule/post', [ScheduleController::class, 'upload'])->name('management.employees.schedule.post');
    Route::get('/management_employees/schedule/preview/{schedule}', [ScheduleController::class, 'preview'])->name('management.employees.schedule.preview');
    Route::get('/management_employees/schedule/latest', [ScheduleController::class, 'latest'])->name('management.employees.schedule.latest');
    Route::get('/management_employees/schedule/download/{schedule}', [ScheduleController::class, 'download'])->name('management.employees.schedule.download');

    //Nomina
    Route::post('/management_employees/payroll/store', [SpreadsheetsController::class, 'store_payroll'])->name('payroll.store');
    Route::get('/management_employees/payroll/state/{payroll_id}', [SpreadsheetsController::class, 'update_payroll_state'])->name('payroll.state.update');
    Route::put('/management_employees/payroll/salary/{payroll_details_id}/update', [SpreadsheetsController::class, 'update_payroll_salary'])->name('payroll.payment.salary.store');
    Route::put('/management_employees/payroll/travelExpense/{payroll_details_id}/update', [SpreadsheetsController::class, 'update_payroll_travelExpense'])->name('payroll.payment.travelExpense.store');
    Route::get('/management_employees/spreadsheets/payroll/export/{payroll_id}', [SpreadsheetsController::class, 'export'])->name('spreadsheets.payroll.export');
    Route::put('/management_employees/payroll/discount/{payroll_id}', [SpreadsheetsController::class, 'discount_employee'])->name('payroll.discount');


    //Trainings
    Route::get('/management_employees/formation_development/trainings/create/{id?}', [FormationDevelopment::class, 'trainings_create'])->name('management.employees.formation_development.trainings.create');
    Route::post('/management_employees/formation_development/trainings/store/{id?}', [FormationDevelopment::class, 'trainings_store'])->name('management.employees.formation_development.trainings.store');

    //Program Formation
    Route::get('/formation_development/formation_programs/create', [FormationDevelopment::class, 'formation_programs_create'])->name('management.employees.formation_development.formation_programs.create');
    Route::post('/management_employees/formation_development/formation_programs/store', [FormationDevelopment::class, 'formation_programs_store'])->name('management.employees.formation_development.formation_programs.store');

    //Program Assignment
    Route::get('/management_employees/formation_development/assignation-create', [FormationDevelopment::class, 'assignate_create'])->name('management.employees.formation_development.assignation.create');
    Route::post('/management_employees/formation_development/assignation-store', [FormationDevelopment::class, 'assignate_store'])->name('management.employees.formation_development.assignation.store');

    //Employees in programs
    Route::post('/management_employees/formation_development/employees_in_programs/{efp_id}', [FormationDevelopment::class, 'employees_in_programs_update'])->name('management.employees.formation_development.employees_in_programs.update');

    //Vacation
    Route::get('/management_vacation/information_additional', [VacationController::class, 'create'])->name('management.vacation.information.create');
    Route::post('/management_vacation/information_additional/store', [VacationController::class, 'store'])->name('management.vacation.information.store');
    Route::get('/management_vacation/information_additional/{vacation}', [VacationController::class, 'edit'])->name('management.vacation.information.edit');
    Route::put('/management_vacation/information_additional/{vacation}/update', [VacationController::class, 'update'])->name('management.vacation.information.update');

    //Document
    Route::post('/documents/store', [DocumentController::class, 'create'])
        ->middleware('permission:' . HumanResourcesPermissions::DOCUMENTS_CREATE->value)
        ->name('documents.create');
    Route::post('/documents/update/{id}', [DocumentController::class, 'update'])
        ->middleware('permission:' . HumanResourcesPermissions::DOCUMENTS_UPDATE->value)
        ->name('documents.update');

    //DocumentSections
    Route::get('/document_sections', [DocumentController::class, 'showSections'])
        ->middleware('permission:' . HumanResourcesPermissions::DOCUMENTS_SECTIONS->value)
        ->name('documents.sections');
    Route::post('/document_sections', [DocumentController::class, 'storeSection'])->name('documents.storeSection');
    Route::put('/document_sections/{section}/update', [DocumentController::class, 'updateSection'])->name('documents.updateSection');
    Route::delete('/document_sections/{section}/delete', [DocumentController::class, 'destroySection'])->name('documents.destroySection');
    Route::get('/document_sections/{sectionId}/zipdownload', [DocumentController::class, 'downloadSectionDocumentsZip'])->name('documents.zipSection');
    //Route::delete('/document_sections/{sectionId}/zipdelete', [DocumentController::class, 'deleteSectionZip'])->name('documents.deleteZipSection');

    //Subdivisions
    Route::get('/document_sections/{section}/subdivisions', [DocumentController::class, 'showSubdivisions'])->name('documents.subdivisions');
    Route::post('/document_sections/{section}/subdivisions/post', [DocumentController::class, 'storeSubdivision'])->name('documents.storeSubdivision');
    Route::put('/document_sections/{section}/subdivisions/{subdivision}/update', [DocumentController::class, 'updateSubdivision'])->name('documents.updateSubdivision');
    Route::delete('/document_sections/{section}/subdivisions/{subdivision}/delete', [DocumentController::class, 'destroySubdivision'])->name('documents.destroySubdivision');
    Route::get('/document_sections/{section}/subdivisions/{subdivisionId}/zipdownload', [DocumentController::class, 'downloadSubdivisionDocumentsZip'])->name('documents.zipSubdivision');
    //Route::delete('/document_sections/{section}/subdivisions/{subdivisionId}/zipdelete', [DocumentController::class, 'deleteZip'])->name('documents.deleteZipSubdivision');

    Route::post('/document_rrhh_status/store/{dr_id?}', [DocumentSpreedSheetController::class, 'store'])
        ->middleware('permission:' . HumanResourcesPermissions::DOCUMENT_RRHH_STATUS_STORE->value)
        ->name('document.rrhh.status.store');
    Route::delete('/document_rrhh_status/destroy/{dr_id}', [DocumentSpreedSheetController::class, 'destroy'])->name('document.rrhh.status.destroy');
    Route::post('/document_rrhh_status/insurance_exp_date', [DocumentSpreedSheetController::class, 'insurance_exp_date'])->name('document.rrhh.status.in_expdate');
});

Route::middleware('permission:' . implode('|', RolesConstants::HR_MODULE))->group(function () {
    Route::get('/management_employees/index', [ManagementEmployees::class, 'index'])
        ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES->value)
        ->name('management.employees');
    Route::get('/management_employees/information_additional/details/{id}', [ManagementEmployees::class, 'details'])
        ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_SHOW->value)
        ->name('management.employees.show');
    Route::get('/management_employees/information_additional/details/download/{id}', [ManagementEmployees::class, 'download'])->name('management.employees.information.details.download');
    Route::post('/management_employees/index-search', [ManagementEmployees::class, 'search'])->name('management.employees.search');

    Route::get('/management_employees/happy_birthday', [ManagementEmployees::class, 'happy_birthday'])
        ->middleware('permission:' . HumanResourcesPermissions::MANAGEMENT_EMPLOYEES_HAPPY_BIRTHDAY->value)
        ->name('management.employees.happy.birthday');

    //Nomina
    Route::get('/management_employees/payroll', [SpreadsheetsController::class, 'index'])->name('payroll.index');
    Route::any('/management_employees/spreadsheets/{payroll_id}', [SpreadsheetsController::class, 'index_payroll'])->name('spreadsheets.index');
    Route::get('/management_employees/spreadsheets_details/{payroll_details_id}/employee/{employee_id}', [SpreadsheetsController::class, 'index_payroll_detail'])->name('spreadsheets.details.index');

    //Formation and Development
    Route::get('/management_employees/formation_development', [FormationDevelopment::class, 'index'])->name('management.employees.formation_development');
    Route::get('/management_employees/formation_development/trainings', [FormationDevelopment::class, 'trainings_index'])->name('management.employees.formation_development.trainings');
    Route::get('/management_employees/formation_development/formation_programs', [FormationDevelopment::class, 'formation_programs_index'])->name('management.employees.formation_development.formation_programs');
    Route::get('/management_employees/formation_development/employees_in_programs', [FormationDevelopment::class, 'employees_in_programs'])->name('management.employees.formation_development.employees_in_programs');
    Route::get('/management_employees/formation_development/alarms', [FormationDevelopment::class, "employees_in_programs_alarms"])->name("employees_in_programs.alarms");
    Route::get('/management_employees/formation_development/view/{id}', [FormationDevelopment::class, 'formation_programs_view'])->name('management.employees.formation_development.view');

    //Employees in programs Alarm
    Route::get('/management_employees/formation_development/employees_in_programs/detail/{employee_id}', [FormationDevelopment::class, 'employees_in_programs_details'])->name('management.employees.formation_development.detail');

    //Vacation
    Route::get('/management_vacation/index/{review?}', [VacationController::class, 'index'])->name('management.vacation');
    Route::get('/management_vacation/information_additional/{vacation}/details', [VacationController::class, 'details'])->name('management.vacation.information.details');
    Route::get('/management_vacation/information_additional/{id}/showDocument', [VacationController::class, 'showDocument'])->name('management.vacation.information.documents.show');

    //Alarm Permissions and Vacation
    Route::get('/permissions/alarm', [VacationController::class, 'alarmPermissions'])->name('alarm.permissions');
    Route::get('/vacation/alarm', [VacationController::class, 'alarmVacation'])->name('alarm.vacation');

    //Document
    Route::get('/documents/index', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}/download', [DocumentController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'showDocument'])->name('documents.show');
    Route::get('/documents/filter/{section}/section/{request?}', [DocumentController::class, 'sectionFilter'])->name('documents.filter.section');
    Route::get('/documents/filter/{section}/section/{subdivision}/subdivision/{request?}', [DocumentController::class, 'subdivisionFilter'])->name('documents.filter.subdivision');
    Route::get('/documents/search/{section}/{subdivision}/{request}/get', [DocumentController::class, 'search'])->name('documents.search');

    //Document Spreed Sheet
    Route::any('/documents_rrhh_status', [DocumentSpreedSheetController::class, 'index'])->name('document.rrhh.status');
    Route::get('/documents_rrhh_status/{emp_id}/{type}', [DocumentSpreedSheetController::class, 'employee_document_alarms'])->name('employee.document.rrhh.status');
    Route::get('/document_rrhh_status_alarm', [DocumentSpreedSheetController::class, 'employeesDocumentAlarms'])->name('document.rrhh.status.alarms');
    Route::get('/document_rrhh_nodoc_alarm', [DocumentSpreedSheetController::class, 'employeesNoDocumentAlarms'])->name('document.rrhh.nodoc.alarms');


    Route::get('/documents/grupal_document', [GrupalDocumentController::class, 'index'])
        ->middleware('permission:' . HumanResourcesPermissions::DOCUMENT_GRUPAL_DOCUMENTS_INDEX->value)
        ->name('document.grupal_documents.index');
    Route::post('/documents/grupal_document/store', [GrupalDocumentController::class, 'store'])->name('document.grupal_documents.store');
    Route::post('/documents/grupal_document/update/{gd_id}', [GrupalDocumentController::class, 'update'])->name('document.grupal_documents.update');
    Route::delete('/documents/grupal_document/destroy/{gd_id}', [GrupalDocumentController::class, 'destroy'])->name('document.grupal_documents.destroy');
    Route::get('/documents/grupal_document/download/{gd_id}', [GrupalDocumentController::class, 'download'])->name('document.grupal_documents.download');

    Route::get('/documents/megaupdate/', [DocumentController::class, 'megaupdate'])->name('documents.megaupdate');
});


Route::get('/management_employees/worker_data/{employee_id}', [SpreadsheetsController::class, 'index_worder_data'])->name('index.worker.data');
