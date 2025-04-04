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
    case DOCUMENT_DELETE = 'document_delete';

    


    //////////////////////
    case MANAGEMENT_EMPLOYEES_CREATE = 'management_employees_create';
    case MANAGEMENT_EMPLOYEES_SHOW = 'management_employees_show';
    case MANAGEMENT_EMPLOYEES_EDIT = 'management_employees_edit';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
