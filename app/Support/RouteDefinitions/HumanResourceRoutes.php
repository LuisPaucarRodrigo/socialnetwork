<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\HumanResource\DocumentSpreedSheetController;
use App\Http\Controllers\HumanResource\ControlEmployees;
use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\FormationDevelopment;
use App\Http\Controllers\HumanResource\GrupalDocumentController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use App\Http\Controllers\HumanResource\VacationController;


class HumanResourceRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'management_employees/destroy/{id}',
                'method' => 'delete',
                'action' => [ManagementEmployees::class, 'destroy'],
                'permission' => true,
                'name' => 'management.employees.destroy',
            ],
            [
                'uri' => 'management_employees/fired/{id}',
                'method' => 'post',
                'action' => [ManagementEmployees::class, 'fired'],
                'permission' => true,
                'name' => 'management.employees.fired',
            ],
            [
                'uri' => 'management_employees/{id}/reentry',
                'method' => 'put',
                'action' => [ManagementEmployees::class, 'reentry'],
                'permission' => true,
                'name' => 'management.employees.reentry',
            ],
            [
                'uri' => 'management_employees/formation_development/delete/{id}',
                'method' => 'delete',
                'action' => [FormationDevelopment::class, 'formation_programs_destroy'],
                'permission' => true,
                'name' => 'management.employees.formation_development.delete',
            ],
            [
                'uri' => 'management_employees/formation_development/delete-employee/{efp_id}',
                'method' => 'delete',
                'action' => [FormationDevelopment::class, 'formation_programs_destroy_employee'],
                'permission' => true,
                'name' => 'management.employees.formation_development.employee.delete',
            ],
            [
                'uri' => 'management_employees/formation_development/trainings/delete/{id}',
                'method' => 'delete',
                'action' => [FormationDevelopment::class, 'trainings_destroy'],
                'permission' => true,
                'name' => 'management.employees.formation_development.trainings.destroy',
            ],
            [
                'uri' => 'management_vacation/information_additional/{vacation}/review',
                'method' => 'get',
                'action' => [VacationController::class, 'review'],
                'permission' => true,
                'name' => 'management.vacation.information.review',
            ],
            [
                'uri' => 'management_vacation/information_additional/reviewed/decline',
                'method' => 'post',
                'action' => [VacationController::class, 'reviewed_and_decline'],
                'permission' => true,
                'name' => 'management.vacation.information.reviewed_decline',
            ],
            [
                'uri' => 'management_vacation/information_additional/{vacation}/delete',
                'method' => 'delete',
                'action' => [VacationController::class, 'destroy'],
                'permission' => true,
                'name' => 'management.vacation.information.destroy',
            ],
            [
                'uri' => 'documents/{id}/delete',
                'method' => 'delete',
                'action' => [DocumentController::class, 'destroy'],
                'permission' => true,
                'name' => 'documents.destroy',
            ],

            [
                'uri' => 'management_employees/information_additional',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'create'],
                'permission' => true,
                'name' => 'management.employees.create',
            ],
            [
                'uri' => 'management_employees/information_additional/create',
                'method' => 'post',
                'action' => [ManagementEmployees::class, 'store'],
                'permission' => true,
                'name' => 'management.employees.store',
            ],
            [
                'uri' => 'management_employees/edit/{id}',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'edit'],
                'permission' => true,
                'name' => 'management.employees.edit',
            ],
            [
                'uri' => 'management_employees/update/{id}',
                'method' => 'post',
                'action' => [ManagementEmployees::class, 'update'],
                'permission' => true,
                'name' => 'management.employees.update',
            ],
            [
                'uri' => 'management_employees/show_preview/doc_alta/{id}',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'show_preview_doc_alta'],
                'permission' => true,
                'name' => 'management.employees.show.preview.doc_alta',
            ],

            //Empleados Externos

            [
                'uri' => 'management_employees/external/index',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'external_index'],
                'permission' => true,
                'name' => 'employees.external.index',
            ],
            [
                'uri' => 'management_employees/external/search',
                'method' => 'post',
                'action' => [ManagementEmployees::class, 'external_search'],
                'permission' => true,
                'name' => 'employees.external.search',
            ],
            [
                'uri' => 'management_employees/storeorupdate/{external_id?}',
                'method' => 'post',
                'action' => [ManagementEmployees::class, 'storeorupdate'],
                'permission' => true,
                'name' => 'management.external.storeorupdate',
            ],
            [
                'uri' => 'management_employees/external/delete/{id}',
                'method' => 'delete',
                'action' => [ManagementEmployees::class, 'external_delete'],
                'permission' => true,
                'name' => 'employees.external.delete',
            ],
            [
                'uri' => 'management_employees/external/preview/{external_preview_id}/curriculum_vitae',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'preview_curriculum_vitae'],
                'permission' => true,
                'name' => 'employees.external.preview.curriculum_vitae',
            ],

            //Control of Employees
            [
                'uri' => 'management_employees/control_employees/index',
                'method' => 'any',
                'action' => [ControlEmployees::class, 'control_employees_index'],
                'permission' => true,
                'name' => 'controlEmployees.index',
            ],
            [
                'uri' => 'management_employees/fixed_documentation/index',
                'method' => 'any',
                'action' => [ControlEmployees::class, 'fixed_documentation_index'],
                'permission' => true,
                'name' => 'controlEmployees.fixed.documentation.index',
            ],
            [
                'uri' => 'management_employees/fixed_documentation/storeOrUpdate/{fixed_documentation_id?}',
                'method' => 'post',
                'action' => [ControlEmployees::class, 'fixed_documentation_storeOrUpdate'],
                'permission' => true,
                'name' => 'controlEmployees.fixed.documentation.store',
            ],
            [
                'uri' => 'management_employees/entry_documentation/index',
                'method' => 'any',
                'action' => [ControlEmployees::class, 'entry_document_index'],
                'permission' => true,
                'name' => 'controlEmployees.entry.document.index',
            ],
            [
                'uri' => 'management_employees/entry_documentation/storeOrUpdate/{fixed_documentation_id?}',
                'method' => 'post',
                'action' => [ControlEmployees::class, 'entry_document_storeOrUpdate'],
                'permission' => true,
                'name' => 'controlEmployees.entry.document.storeOrUpdate',
            ],
            [
                'uri' => 'management_employees/issuance_documentation/index',
                'method' => 'any',
                'action' => [ControlEmployees::class, 'issuance_documentation_index'],
                'permission' => true,
                'name' => 'controlEmployees.issuance.documentation.index',
            ],
            [
                'uri' => 'management_employees/issuance_documentation/storeOrUpdate/{fixed_documentation_id?}',
                'method' => 'post',
                'action' => [ControlEmployees::class, 'issuance_documentation_storeOrUpdate'],
                'permission' => true,
                'name' => 'controlEmployees.issuance.documentation.storeOrUpdate',
            ],



            //Trainings

            [
                'uri' => 'management_employees/formation_development/trainings/create/{id?}',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'trainings_create'],
                'permission' => true,
                'name' => 'management.employees.formation_development.trainings.create',
            ],
            [
                'uri' => 'management_employees/formation_development/trainings/store/{id?}',
                'method' => 'post',
                'action' => [FormationDevelopment::class, 'trainings_store'],
                'permission' => true,
                'name' => 'management.employees.formation_development.trainings.store',
            ],

            //Program Formation
            [
                'uri' => 'formation_development/formation_programs/create',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'formation_programs_create'],
                'permission' => true,
                'name' => 'management.employees.formation_development.formation_programs.create',
            ],
            [
                'uri' => 'management_employees/formation_development/formation_programs/store',
                'method' => 'post',
                'action' => [FormationDevelopment::class, 'formation_programs_store'],
                'permission' => true,
                'name' => 'management.employees.formation_development.formation_programs.store',
            ],

            //Program Assignment
            [
                'uri' => 'management_employees/formation_development/assignation-create',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'assignate_create'],
                'permission' => true,
                'name' => 'management.employees.formation_development.assignation.create',
            ],
            [
                'uri' => 'management_employees/formation_development/assignation-store',
                'method' => 'post',
                'action' => [FormationDevelopment::class, 'assignate_store'],
                'permission' => true,
                'name' => 'management.employees.formation_development.assignation.store',
            ],

            //Employees in programs
            [
                'uri' => 'management_employees/formation_development/employees_in_programs/{efp_id}',
                'method' => 'post',
                'action' => [FormationDevelopment::class, 'employees_in_programs_update'],
                'permission' => true,
                'name' => 'management.employees.formation_development.employees_in_programs.update',
            ],

            //Vacation
            [
                'uri' => 'management_vacation/information_additional',
                'method' => 'get',
                'action' => [VacationController::class, 'create'],
                'permission' => true,
                'name' => 'management.vacation.information.create',
            ],
            [
                'uri' => 'management_vacation/information_additional/store',
                'method' => 'post',
                'action' => [VacationController::class, 'store'],
                'permission' => true,
                'name' => 'management.vacation.information.store',
            ],
            [
                'uri' => 'management_vacation/information_additional/{vacation}',
                'method' => 'get',
                'action' => [VacationController::class, 'edit'],
                'permission' => true,
                'name' => 'management.vacation.information.edit',
            ],
            [
                'uri' => 'management_vacation/information_additional/{vacation}/update',
                'method' => 'put',
                'action' => [VacationController::class, 'update'],
                'permission' => true,
                'name' => 'management.vacation.information.update',
            ],

            //Document
            [
                'uri' => 'documents/store',
                'method' => 'post',
                'action' => [DocumentController::class, 'create'],
                'permission' => true,
                'name' => 'documents.create',
            ],
            [
                'uri' => 'documents/update/{id}',
                'method' => 'post',
                'action' => [DocumentController::class, 'update'],
                'permission' => true,
                'name' => 'documents.update',
            ],

            //DocumentSections
            [
                'uri' => 'document_sections',
                'method' => 'get',
                'action' => [DocumentController::class, 'showSections'],
                'permission' => true,
                'name' => 'documents.sections',
            ],
            [
                'uri' => 'document_sections',
                'method' => 'post',
                'action' => [DocumentController::class, 'storeSection'],
                'permission' => true,
                'name' => 'documents.storeSection',
            ],
            [
                'uri' => 'document_sections/{section}/update',
                'method' => 'post',
                'action' => [DocumentController::class, 'updateSection'],
                'permission' => true,
                'name' => 'documents.updateSection',
            ],
            [
                'uri' => 'document_sections/{section}/delete',
                'method' => 'delete',
                'action' => [DocumentController::class, 'destroySection'],
                'permission' => true,
                'name' => 'documents.destroySection',
            ],
            [
                'uri' => 'document_sections/{sectionId}/zipdownload',
                'method' => 'get',
                'action' => [DocumentController::class, 'downloadSectionDocumentsZip'],
                'permission' => true,
                'name' => 'documents.zipSection',
            ],
            //Subdivisions
            [
                'uri' => 'document_sections/{section}/subdivisions',
                'method' => 'get',
                'action' => [DocumentController::class, 'showSubdivisions'],
                'permission' => true,
                'name' => 'documents.subdivisions',
            ],
            [
                'uri' => 'document_sections/{section}/subdivisions/post',
                'method' => 'post',
                'action' => [DocumentController::class, 'storeSubdivision'],
                'permission' => true,
                'name' => 'documents.storeSubdivision',
            ],
            [
                'uri' => 'document_sections/{section}/subdivisions/{subdivision}/update',
                'method' => 'post',
                'action' => [DocumentController::class, 'updateSubdivision'],
                'permission' => true,
                'name' => 'documents.updateSubdivision',
            ],
            [
                'uri' => 'document_sections/{section}/subdivisions/{subdivision}/delete',
                'method' => 'delete',
                'action' => [DocumentController::class, 'destroySubdivision'],
                'permission' => true,
                'name' => 'documents.destroySubdivision',
            ],
            [
                'uri' => 'document_sections/{section}/subdivisions/{subdivisionId}/zipdownload',
                'method' => 'get',
                'action' => [DocumentController::class, 'downloadSubdivisionDocumentsZip'],
                'permission' => true,
                'name' => 'documents.zipSubdivision',
            ],

            // DocumentRRHH Status
            [
                'uri' => 'document_rrhh_status/store/{dr_id?}',
                'method' => 'post',
                'action' => [DocumentSpreedSheetController::class, 'store'],
                'permission' => true,
                'name' => 'document.rrhh.status.store',
            ],
            [
                'uri' => 'document_rrhh_status/destroy/{dr_id}',
                'method' => 'delete',
                'action' => [DocumentSpreedSheetController::class, 'destroy'],
                'permission' => true,
                'name' => 'document.rrhh.status.destroy',
            ],
            [
                'uri' => 'document_rrhh_status/insurance_exp_date',
                'method' => 'post',
                'action' => [DocumentSpreedSheetController::class, 'insurance_exp_date'],
                'permission' => true,
                'name' => 'document.rrhh.status.in_expdate',
            ],
            // Management Employees
            [
                'uri' => 'management_employees/index',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'index'],
                'permission' => true,
                'name' => 'management.employees',
            ],
            [
                'uri' => 'management_employees/information_additional/details/{id}',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'details'],
                'permission' => true,
                'name' => 'management.employees.show',
            ],
            [
                'uri' => 'management_employees/information_additional/details/download/{id}',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'download'],
                'permission' => true,
                'name' => 'management.employees.information.details.download',
            ],
            [
                'uri' => 'management_employees/index-search',
                'method' => 'post',
                'action' => [ManagementEmployees::class, 'search'],
                'permission' => true,
                'name' => 'management.employees.search',
            ],

            [
                'uri' => 'management_employees/happy_birthday',
                'method' => 'get',
                'action' => [ManagementEmployees::class, 'happy_birthday'],
                'permission' => true,
                'name' => 'management.employees.happy.birthday',
            ],

            //Nomina
            [
                'uri' => 'management_employees/payroll/store',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll'],
                'permission' => true,
                'name' => 'payroll.store',
            ],
            [
                'uri' => 'management_employees/payroll/state/{payroll_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'update_payroll_state'],
                'permission' => true,
                'name' => 'payroll.state.update',
            ],
            [
                'uri' => 'management_employees/payroll',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'index'],
                'permission' => true,
                'name' => 'payroll.index',
            ],
            [
                'uri' => 'management_employees/spreadsheets/{payroll_id}',
                'method' => 'any',
                'action' => [SpreadsheetsController::class, 'index_payroll'],
                'permission' => true,
                'name' => 'spreadsheets.index',
            ],
            [
                'uri' => 'managemet_employees/spreadsheets/{payroll_detail_id}/generate_bill',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'generate_payroll_bill'],
                'permission' => false,
                'name' => 'spreadsheets.generate.bill',
            ],
            [
                'uri' => 'management_employees/worker_data/{employee_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'index_worder_data'],
                'permission' => true,
                'name' => 'index.worker.data',
            ],
            [
                'uri' => 'management_employees/payroll_show_pdws/{payroll_detail_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'show_payroll_detail_work_schedule'],
                'permission' => true,
                'name' => 'payroll.show.payroll.detail.work.schedule',
            ],
            [
                'uri' => 'management_employees/payroll_store_pdws',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll_detail_work_schedule'],
                'permission' => true,
                'name' => 'payroll.store.payroll.detail.work.schedule',
            ],
            [
                'uri' => 'management_employees/payroll_show_pdmi/{payroll_detail_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'show_payroll_detail_monetary_income'],
                'permission' => true,
                'name' => 'payroll.show.payroll.detail.monetary.income',
            ],
            [
                'uri' => 'management_employees/payroll_store_pdmi',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll_monetary_income'],
                'permission' => true,
                'name' => 'payroll.store.payroll.detail.monetary.income',
            ],
            [
                'uri' => 'management_employees/payroll_show_pdmd/{payroll_detail_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'show_payroll_detail_monetary_discount'],
                'permission' => true,
                'name' => 'payroll.show.payroll.detail.monetary.discount',
            ],
            [
                'uri' => 'management_employees/payroll_store_pdmd',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll_monetary_discount'],
                'permission' => true,
                'name' => 'payroll.store.payroll.detail.monetary.discount',
            ],
            [
                'uri' => 'management_employees/payroll_show_pdtac/{payroll_detail_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'show_payroll_detail_tax_and_contribution'],
                'permission' => true,
                'name' => 'payroll.show.payroll.detail.tax.contribution',
            ],
            [
                'uri' => 'management_employees/payroll_store_pdtac',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll_tax_and_contribution'],
                'permission' => true,
                'name' => 'payroll.store.payroll.detail.tax.contribution',
            ],
            [
                'uri' => 'management_employees/payroll_external_detail_index/{payroll_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'index_payroll_external_detail'],
                'permission' => true,
                'name' => 'payroll.index.payroll.external.detail',
            ],
            [
                'uri' => 'management_employees/payroll_external_detail_store',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll_external_detail'],
                'permission' => true,
                'name' => 'payroll.store.payroll.external.detail',
            ],
            [
                'uri' => 'management_employees/payroll_external_detail_delete/{payroll_detail_id}',
                'method' => 'delete',
                'action' => [SpreadsheetsController::class, 'destroy_payroll_external_detail'],
                'permission' => true,
                'name' => 'payroll.store.payroll.external.destroy',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expenses_multiple_store',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_pay_spreedsheets'],
                'permission' => true,
                'name' => 'payroll.detail.expenses.multiple.store',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expense_constants_index',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'show_payroll_detail_expense_constants'],
                'permission' => true,
                'name' => 'payroll.detail.expense.constants.show',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expense_index/{payroll_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'index_payroll_detail_expense'],
                'permission' => true,
                'name' => 'payroll.detail.expense.index',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expense_search/{payroll_id}',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'search_payroll_detail_expenses'],
                'permission' => true,
                'name' => 'payroll.detail.expense.search',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expense_store',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'store_payroll_detail_expense'],
                'permission' => true,
                'name' => 'payroll.detail.expenses.store',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expense_destroy/{payroll_detail_expense_id}',
                'method' => 'delete',
                'action' => [SpreadsheetsController::class, 'destroy_payroll_detail_expense'],
                'permission' => true,
                'name' => 'payroll.detail.expense.destroy',
            ],
            [
                'uri' => 'management_employees/payroll_detail_expense_masive_opnuda_update',
                'method' => 'post',
                'action' => [SpreadsheetsController::class, 'masive_update_payroll_detail_expense'],
                'permission' => true,
                'name' => 'payroll.detail.expenses.massive.update.opnuda',
            ],
            [
                'uri' => 'management_employees/payroll_detail_export/{payroll_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'export_excel_payroll_detail'],
                'permission' => true,
                'name' => 'payroll.detail.export',
            ],
            [
                'uri' => 'management_employees/payroll_detail_export_details/{payroll_id}',
                'method' => 'get',
                'action' => [SpreadsheetsController::class, 'export_detail_excel_payroll_detail'],
                'permission' => true,
                'name' => 'payroll.detail.export.details',
            ],


            // Formation and Development
            [
                'uri' => 'management_employees/formation_development',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'index'],
                'permission' => true,
                'name' => 'management.employees.formation_development',
            ],
            [
                'uri' => 'management_employees/formation_development/trainings',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'trainings_index'],
                'permission' => true,
                'name' => 'management.employees.formation_development.trainings',
            ],
            [
                'uri' => 'management_employees/formation_development/formation_programs',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'formation_programs_index'],
                'permission' => true,
                'name' => 'management.employees.formation_development.formation_programs',
            ],
            [
                'uri' => 'management_employees/formation_development/employees_in_programs',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'employees_in_programs'],
                'permission' => true,
                'name' => 'management.employees.formation_development.employees_in_programs',
            ],
            [
                'uri' => 'management_employees/formation_development/alarms',
                'method' => 'get',
                'action' => [FormationDevelopment::class, "employees_in_programs_alarms"],
                'permission' => true,
                'name' => 'employees_in_programs.alarms',
            ],
            [
                'uri' => 'management_employees/formation_development/view/{id}',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'formation_programs_view'],
                'permission' => true,
                'name' => 'management.employees.formation_development.view',
            ],

            // Employees in programs Alarm
            [
                'uri' => 'management_employees/formation_development/employees_in_programs/detail/{employee_id}',
                'method' => 'get',
                'action' => [FormationDevelopment::class, 'employees_in_programs_details'],
                'permission' => true,
                'name' => 'management.employees.formation_development.detail',
            ],

            // Vacation
            [
                'uri' => 'management_vacation/index/{review?}',
                'method' => 'get',
                'action' => [VacationController::class, 'index'],
                'permission' => true,
                'name' => 'management.vacation',
            ],
            [
                'uri' => 'management_vacation/information_additional/{vacation}/details',
                'method' => 'get',
                'action' => [VacationController::class, 'details'],
                'permission' => true,
                'name' => 'management.vacation.information.details',
            ],
            [
                'uri' => 'management_vacation/information_additional/{id}/showDocument',
                'method' => 'get',
                'action' => [VacationController::class, 'showDocument'],
                'permission' => true,
                'name' => 'management.vacation.information.documents.show',
            ],
            // Alarm Permissions and Vacation
            [
                'uri' => 'permissions/alarm',
                'method' => 'get',
                'action' => [VacationController::class, 'alarmPermissions'],
                'permission' => true,
                'name' => 'alarm.permissions',
            ],
            [
                'uri' => 'vacation/alarm',
                'method' => 'get',
                'action' => [VacationController::class, 'alarmVacation'],
                'permission' => true,
                'name' => 'alarm.vacation',
            ],

            // Document
            [
                'uri' => 'documents/index',
                'method' => 'get',
                'action' => [DocumentController::class, 'index'],
                'permission' => true,
                'name' => 'documents.index',
            ],
            [
                'uri' => 'documents/{document}/download',
                'method' => 'get',
                'action' => [DocumentController::class, 'downloadDocument'],
                'permission' => true,
                'name' => 'documents.download',
            ],
            [
                'uri' => 'documents/{document}/preview',
                'method' => 'get',
                'action' => [DocumentController::class, 'showDocument'],
                'permission' => true,
                'name' => 'documents.show',
            ],
            [
                'uri' => 'documents/filter/{section}/section/{request?}',
                'method' => 'get',
                'action' => [DocumentController::class, 'sectionFilter'],
                'permission' => true,
                'name' => 'documents.filter.section',
            ],
            [
                'uri' => 'documents/filter/{section}/section/{subdivision}/subdivision/{request?}',
                'method' => 'get',
                'action' => [DocumentController::class, 'subdivisionFilter'],
                'permission' => true,
                'name' => 'documents.filter.subdivision',
            ],
            [
                'uri' => 'documents/search/{section}/{subdivision}/{request}/get',
                'method' => 'get',
                'action' => [DocumentController::class, 'search'],
                'permission' => true,
                'name' => 'documents.search',
            ],

            // Document Spreed Sheet
            [
                'uri' => 'documents_rrhh_status',
                'method' => 'any',
                'action' => [DocumentSpreedSheetController::class, 'index'],
                'permission' => true,
                'name' => 'document.rrhh.status',
            ],
            [
                'uri' => 'document_rrhh_status/create/',
                'method' => 'post',
                'action' => [DocumentSpreedSheetController::class, 'create'],
                'permission' => true,
                'name' => 'document.rrhh.status.create',
            ],
            [
                'uri' => 'document_rrhh_status/update/{dr_id}',
                'method' => 'post',
                'action' => [DocumentSpreedSheetController::class, 'update'],
                'permission' => true,
                'name' => 'document.rrhh.status.update',
            ],
            [
                'uri' => 'documents_rrhh_status/{emp_id}/{type}',
                'method' => 'get',
                'action' => [DocumentSpreedSheetController::class, 'employee_document_alarms'],
                'permission' => true,
                'name' => 'employee.document.rrhh.status',
            ],
            [
                'uri' => 'document_rrhh_status_alarm',
                'method' => 'get',
                'action' => [DocumentSpreedSheetController::class, 'employeesDocumentAlarms'],
                'permission' => true,
                'name' => 'document.rrhh.status.alarms',
            ],
            [
                'uri' => 'document_rrhh_nodoc_alarm',
                'method' => 'get',
                'action' => [DocumentSpreedSheetController::class, 'employeesNoDocumentAlarms'],
                'permission' => true,
                'name' => 'document.rrhh.nodoc.alarms',
            ],

            // Grupal Documents
            [
                'uri' => 'documents/grupal_document',
                'method' => 'get',
                'action' => [GrupalDocumentController::class, 'index'],
                'permission' => true,
                'name' => 'document.grupal_documents.index',
            ],
            [
                'uri' => 'documents/grupal_document/store',
                'method' => 'post',
                'action' => [GrupalDocumentController::class, 'store'],
                'permission' => true,
                'name' => 'document.grupal_documents.store',
            ],
            [
                'uri' => 'documents/grupal_document/update/{gd_id}',
                'method' => 'post',
                'action' => [GrupalDocumentController::class, 'update'],
                'permission' => true,
                'name' => 'document.grupal_documents.update',
            ],
            [
                'uri' => 'documents/grupal_document/destroy/{gd_id}',
                'method' => 'delete',
                'action' => [GrupalDocumentController::class, 'destroy'],
                'permission' => true,
                'name' => 'document.grupal_documents.destroy',
            ],
            [
                'uri' => 'documents/grupal_document/download/{gd_id}',
                'method' => 'get',
                'action' => [GrupalDocumentController::class, 'download'],
                'permission' => true,
                'name' => 'document.grupal_documents.download',
            ],
            [
                'uri' => 'management_employees_documents_masive_create/{employee_id}',
                'method' => 'post',
                'action' => [DocumentSpreedSheetController::class, 'createMasive'],
                'permission' => false,
                'name' => 'management.employees.document.register.masive',
            ],
        ];
    }
}
