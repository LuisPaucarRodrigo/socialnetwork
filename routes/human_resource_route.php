<?php

use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\FormationDevelopment;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\ScheduleController;
use App\Http\Controllers\HumanResource\SectionController;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use App\Http\Controllers\HumanResource\VacationController;
use Illuminate\Support\Facades\Route;

Route::get('/management_employees/index/{reentry?}', [ManagementEmployees::class, 'index'])->name('management.employees');
Route::get('/management_employees/information_additional', [ManagementEmployees::class, 'index_info_additional'])->name('management.employees.information');
Route::post('/management_employees/information_additional/create', [ManagementEmployees::class, 'create'])->name('management.employees.information.create');
Route::get('/management_employees/information_additional/details/{id}', [ManagementEmployees::class, 'details'])->name('management.employees.information.details');
Route::get('/management_employees/information_additional/details/download/{id}', [ManagementEmployees::class, 'download'])->name('management.employees.information.details.download');

Route::get('/management_employees/index-search', [ManagementEmployees::class, 'search'])->name('management.employees.search');


// Route::get('/management_employees/edit/{id}', [ManagementEmployees::class, 'edit'])->name('management.employees.edit');
// Route::post('/management_employees/update/{id}', [ManagementEmployees::class, 'update'])->name('management.employees.update');
// Route::delete('/management_employees/destroy/{id}', [ManagementEmployees::class, 'destroy'])->name('management.employees.destroy');
// Route::put('/management_employees/fired/{id}', [ManagementEmployees::class, 'fired'])->name('management.employees.fired');
// Route::put('/management_employees/{id}/reentry', [ManagementEmployees::class, 'reentry'])->name('management.employees.reentry');

//Schedule
Route::get('/management_employees/schedule/index', [ScheduleController::class, 'index'])->name('management.employees.schedule.index');
Route::post('/management_employees/schedule/post', [ScheduleController::class, 'upload'])->name('management.employees.schedule.post');
Route::get('/management_employees/schedule/preview/{schedule}', [ScheduleController::class, 'preview'])->name('management.employees.schedule.preview');
Route::get('/management_employees/schedule/latest', [ScheduleController::class, 'latest'])->name('management.employees.schedule.latest');
Route::get('/management_employees/schedule/download/{schedule}', [ScheduleController::class, 'download'])->name('management.employees.schedule.download');


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
Route::get('/formation_development/formation_programs/create', [FormationDevelopment::class, 'formation_programs_create'])->name('management.employees.formation_development.formation_programs.create');
Route::post('/management_employees/formation_development/formation_programs/store', [FormationDevelopment::class, 'formation_programs_store'])->name('management.employees.formation_development.formation_programs.store');

//Trainings
Route::get('/management_employees/formation_development/trainings', [FormationDevelopment::class, 'trainings_index'])->name('management.employees.formation_development.trainings');
Route::get('/management_employees/formation_development/trainings/create/{id?}', [FormationDevelopment::class, 'trainings_create'])->name('management.employees.formation_development.trainings.create');
Route::post('/management_employees/formation_development/trainings/store/{id?}', [FormationDevelopment::class, 'trainings_store'])->name('management.employees.formation_development.trainings.store');
// Route::delete('/management_employees/formation_development/trainings/delete/{id}', [FormationDevelopment::class, 'trainings_destroy'])->name('management.employees.formation_development.trainings.destroy');


//Employees in programs
Route::get('/management_employees/formation_development/employees_in_programs', [FormationDevelopment::class,'employees_in_programs'])->name('management.employees.formation_development.employees_in_programs');
Route::post('/management_employees/formation_development/employees_in_programs/{efp_id}', [FormationDevelopment::class,'employees_in_programs_update'])->name('management.employees.formation_development.employees_in_programs.update');


//Vacation
Route::get('/management_vacation/index/{review?}', [VacationController::class, 'index'])->name('management.vacation');
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

Route::get('/permissions/alarm', [VacationController::class, 'alarmPermissions']);
Route::get('/vacation/alarm', [VacationController::class, 'alarmVacation']);



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

Route::get('/calendar_alarm', [SectionController::class, 'calendarAlarm'])->name('sections.calendar');

