<?php

namespace App\Enums\Permissions;

enum ProjectPermissions: string
{
    
    // PREPROJECTS
    case PREPROJECT_UPDATE = 'preproject_update';
    case PREPROJECT_DELETE = 'preproject_delete';
    case PREPROJECT_PHOTO_REPORT_UPDATE = 'preproject_photo_report_update';
    case PREPROJECT_PHOTO_REPORT_DELETE = 'preproject_photo_report_delete';
    case PREPROJECT_PROVIDERS_QUOTES_DELETE = 'preproject_providers_quotes_delete';
    case PREPROJECT_QUOTE_ITEM_DELETE = 'preproject_quote_item_delete';
    case PREPROJECT_QUOTE_ITEM_STORE = 'preproject_quote_item_store';
    case PREPROJECT_QUOTE_PRODUCT_STORE = 'preproject_quote_product_store';
    case PREPROJECT_QUOTE_PRODUCT_DELETE = 'preproject_quote_product_delete';

    // PROJECTS
    case PROJECT_UPDATE = 'project_update';
    case PROJECT_DELETE = 'project_delete';
    case PROJECT_DELETE_EMPLOYEE = 'project_delete_employee';

    // TASKS
    case TASK_DELETE_EMPLOYEE = 'task_delete_employee';
    case TASK_DELETE = 'task_delete';

    // CICSA SECTIONS & SUBSECTIONS
    case CICSA_SUBSECTION_UPDATE = 'cicsa_subsection_update';
    case CICSA_SUBSECTION_DELETE = 'cicsa_subsection_delete';
    case CICSA_SECTION_DELETE = 'cicsa_section_delete';

    
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
