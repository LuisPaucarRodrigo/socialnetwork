<?php

namespace App\Enums\Permissions;

enum HumanResourcesPermissions: string
{

    // HUMAN RESOURCE
    case MANAGEMENT_EMPLOYEES = 'management_employees';
    case MANAGEMENT_EMPLOYEES_DELETE = 'management_employees_delete';
    case MANAGEMENT_EMPLOYEES_FIRED = 'management_employees_fired';
    case MANAGEMENT_EMPLOYEES_REENTRY = 'management_employees_reentry';

    // FORMATION DEVELOPMENT PROGRAM
    case FORMATION_DEVELOPMENT_DELETE = 'formation_development_delete';
    case FORMATION_DEVELOPMENT_EMPLOYEE_DELETE = 'formation_development_employee_delete';


    // TRAINING
    case TRAININGS_DELETE = 'trainings_delete';

    // VACATION
    case MANAGEMENT_VACATION_REVIEW = 'management_vacation_review';
    case MANAGEMENT_VACATION_REVIEW_DECLINE = 'management_vacation_review_decline';
    case MANAGEMENT_VACATION_DELETE = 'management_vacation_delete';

    // DOCUMENT
    case DOCUMENTS_CREATE = 'documents_create';
    case DOCUMENT_DELETE = 'document_delete';
    case DOCUMENTS_UPDATE = 'documents_update';
    case DOCUMENTS_SECTIONS = 'documents_sections';
    case DOCUMENT_GRUPAL_DOCUMENTS_INDEX = 'document_grupal_documents_index';


    case MANAGEMENT_EXTERNAL_STOREORUPDATE = 'management_external_storeorupdate';
    case EMPLOYEES_EXTERNAL_INDEX = 'employees_external_index';

    


    //////////////////////
    case MANAGEMENT_EMPLOYEES_CREATE = 'management_employees_create';
    case MANAGEMENT_EMPLOYEES_SHOW = 'management_employees_show';
    case MANAGEMENT_EMPLOYEES_EDIT = 'management_employees_edit';
    case MANAGEMENT_EMPLOYEES_HAPPY_BIRTHDAY = 'management_employees_happy_birthday';



    ////has to become policy
    case ee_image = 'ee_image';
    case ee_costline = 'ee_costline';
    case ee_name = 'ee_name';
    case ee_lastname = 'ee_lastname';
    case ee_dni = 'ee_dni';
    case ee_phone1 = 'ee_phone1';
    case ee_birthdate = 'ee_birthdate';
    case ee_address = 'ee_address';
    case ee_email = 'ee_email';
    case ee_emailcompany = 'ee_emailcompany';
    case ee_salary = 'ee_salary';
    case ee_sctr = 'ee_sctr';
    case ee_curriculum = 'ee_curriculum';
    case ee_actions = 'ee_actions';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
