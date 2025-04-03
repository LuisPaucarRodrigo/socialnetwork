<?php

namespace App\Enums\Permissions;

enum UserPermissions: string
{
    // USERS
    case USER_LINK_EMPLOYEE = 'link_employee';
    case USER_EDIT = 'user_edit';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    // ROLS
    case ROL_VIEW = 'rol_view';
    case ROL_CREATE = 'rol_create';
    case ROL_UPDATE = 'rol_update';
    case ROL_DELETE = 'rol_delete';
    case ROL_DETAILS = 'rol_details';

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

    // PURCHASE REQUESTS
    case PURCHASE_REQUEST_DELETE = 'purchase_request_delete';

    // TASKS
    case TASK_DELETE_EMPLOYEE = 'task_delete_employee';
    case TASK_DELETE = 'task_delete';

    // CICSA SECTIONS & SUBSECTIONS
    case CICSA_SUBSECTION_UPDATE = 'cicsa_subsection_update';
    case CICSA_SUBSECTION_DELETE = 'cicsa_subsection_delete';
    case CICSA_SECTION_DELETE = 'cicsa_section_delete';

    // SPECIAL WAREHOUSES
    case SPECIAL_PRODUCTS_DELETE = 'special_products_delete';
    case SPECIAL_DISPATCH_OUTPUT_DELETE = 'special_dispatch_output_delete';

    // HUMAN RESOURCE
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

    // PURCHASING REQUEST PRODUCTS
    case PURCHASING_REQUEST_PRODUCT_STORE = 'purchasing_request_product_store';
    case PURCHASING_REQUEST_PRODUCT_DELETE = 'purchasing_request_product_delete';

    case DOCUMENT_FOLDERS_VALIDATION = 'folder_validation';
    case DOCUMENT_FOLDERS_CHECK = 'folder_check';
    case DOCUMENT_FOLDERS_INVALIDATE = 'folder_invalidate';
    case DOCUMENT_FOLDERS_PERMISSION_SEE_DOWNLOAD = 'folder_permission_see_download';
    case DOCUMENT_FOLDERS_PERMISSION_CREATE = 'folder_permission_create';
    case DOCUMENT_FOLDERS_PERMISSION_ADD = 'folder_permission_add';
    case DOCUMENT_FOLDERS_PERMISSION_DELETE = 'folder_permission_delete';
    case DOCUMENT_FOLDERS_DELETE = 'folder_delete';
    case DOCUMENT_FOLDERS_PERMISSION_VIEW = 'folder_permission_view';

    case INVENTORY_PURCHASE_PRODUCTS_DISABLE = 'purchase_products_disable';
    case INVENTORY_PURCHASE_PRODUCTS_TYPE_STORE = 'purchase_products_type_store';
    case INVENTORY_PURCHASE_PRODUCTS_RESOURCE_TYPE_STORE = 'purchase_products_resource_type_store';

    case WAREHOUSES_RESOURCE_CREATE = 'resource_create';
    case WAREHOUSES_RESOURCE_STORE = 'resource_store';

    case PROVIDERS_EDIT = 'providers_edit';
    case PROVIDERS_UPDATE = 'providers_update';
    case PROVIDERS_DESTROY = 'providers_destroy';

    case PURCHASESREQUEST_EDIT = 'purchasesrequest_edit';
    case PURCHASESREQUEST_UPDATE = 'purchasesrequest_update';

    case USERS_INDEX = 'users_index';
    case USERS_SEARCH = 'users_search';
    case USERS_DETAILS = 'users_details';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
