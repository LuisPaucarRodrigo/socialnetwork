<?php

use App\Http\Controllers\DocumentSpreedSheetController;
use App\Http\Controllers\HumanResource\ControlEmployees;
use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\FormationDevelopment;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\ScheduleController;
use App\Http\Controllers\HumanResource\SectionController;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use App\Http\Controllers\HumanResource\VacationController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:HumanResourceManager')->group(function () {
    Route::get('/management_employees/information_additional', [ManagementEmployees::class, 'create'])->name('management.employees.create');
    Route::post('/management_employees/information_additional/create', [ManagementEmployees::class, 'store'])->name('management.employees.store');
    Route::get('/management_employees/edit/{id}', [ManagementEmployees::class, 'edit'])->name('management.employees.edit');
    Route::post('/management_employees/update/{id}', [ManagementEmployees::class, 'update'])->name('management.employees.update');

    //Empleados Externos
    Route::get('/management_employees/external/index', [ManagementEmployees::class, 'external_index'])->name('employees.external.index');
    Route::post('/management_employees/storeorupdate/{external_id?}', [ManagementEmployees::class, 'storeorupdate'])->name('management.external.storeorupdate');
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
    Route::post('/management_employees/schedule/update_number_people', [SpreadsheetsController::class, 'update_number_people'])->name('management.employees.schedule.update_number_people');

    //Nomina
    Route::get('/management_employees/pension_system/edit', [SpreadsheetsController::class, 'edit'])->name('pension_system.edit');
    Route::put('/management_employees/pension_system/update/{id}', [SpreadsheetsController::class, 'update'])->name('pension_system.update');
    Route::put('/management_employees/pension_system/update_seg/{id}', [SpreadsheetsController::class, 'update_seg'])->name('pension_system_seg.update');

    //Sctr
    Route::post('/management_employees/sctr_p/update', [SpreadsheetsController::class, 'update_sctr_p'])->name('sctr_p.update');
    Route::post('/management_employees/sctr_s/update', [SpreadsheetsController::class, 'update_sctr_s'])->name('sctr_s.update');

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
    Route::post('/documents/store', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents/update/{id}', [DocumentController::class, 'update'])->name('documents.update');

    //DocumentSections
    Route::get('/document_sections', [DocumentController::class, 'showSections'])->name('documents.sections');
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

    //SubSections
    Route::post('/subSections', [SectionController::class, 'storeSubSection'])->name('sections.storeSubSection');

    //Sections
    Route::get('/sections', [SectionController::class, 'showSections'])->name('sections.sections');
    Route::post('/sections', [SectionController::class, 'storeSection'])->name('sections.storeSection');
    Route::delete('/sections/{section}', [SectionController::class, 'destroySection'])->name('sections.destroySection');


    Route::post('/document_rrhh_status/store/{dr_id?}', [DocumentSpreedSheetController::class, 'store'])->name('document.rrhh.status.store');
    Route::delete('/document_rrhh_status/destroy/{dr_id}', [DocumentSpreedSheetController::class, 'destroy'])->name('document.rrhh.status.destroy');
});

Route::middleware('permission:HumanResourceManager|HumanResource')->group(function () {
    Route::get('/management_employees/index/{reentry?}', [ManagementEmployees::class, 'index'])->name('management.employees');
    Route::get('/management_employees/information_additional/details/{id}', [ManagementEmployees::class, 'details'])->name('management.employees.show');
    Route::get('/management_employees/information_additional/details/download/{id}', [ManagementEmployees::class, 'download'])->name('management.employees.information.details.download');
    Route::get('/management_employees/index-search', [ManagementEmployees::class, 'search'])->name('management.employees.search');

    Route::get('/management_employees/happy_birthday', [ManagementEmployees::class, 'happy_birthday'])->name('management.employees.happy.birthday');

    //Nomina
    Route::get('/management_employees/spreadsheets/{reentry?}', [SpreadsheetsController::class, 'index'])->name('spreadsheets.index');
    Route::get('/management_employees/spreadsheets/payroll/export', [SpreadsheetsController::class, 'export'])->name('spreadsheets.payroll.export');

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
    //SubSections
    Route::get('/subSections', [SectionController::class, 'showSubSections'])->name('sections.subSections');
    Route::get('/subSections/show/{subSection}', [SectionController::class, 'showSubSection'])->name('sections.subSection');
    Route::get('/calendar_alarm', [SectionController::class, 'calendarAlarm'])->name('sections.calendar');

    //Member Alarm
    Route::get('/doTask', [SectionController::class, 'doTask'])->name('sections.alarm');



    //Document Spreed Sheet
    Route::get('/documents_rrhh_status', [DocumentSpreedSheetController::class, 'index'])->name('document.rrhh.status');
    // Route::get('/build_docreg', [DocumentSpreedSheetController::class, 'buildDocReg']);
});
