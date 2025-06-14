<?php

namespace App\Constants;

class RolesConstants
{

    //USERS
    public const user_submodule = [
        'UserGestionManager',
        'UserGestion',
    ];
    public const roles_submodule = [
        'UserRolesManager',
        'UserRoles',
    ];
    public const USERS_MODULE = [
        'UserManager',
        'User',
        ...self::user_submodule,
        ...self::roles_submodule,
    ];

    //HR GROUP
    public const hremployees_submodule = [
        'HumanResourceEmployeesManager',
        'HumanResourceEmployees',
    ];
    public const hreemployees_submodule = [
        'HumanResourceEEmployeesManager',
        'HumanResourceEEmployees',
    ];
    public const hrspreedsheet_submodule = [
        'HumanResourceSpreedsheetManager',
        'HumanResourceSpreedsheet',
    ];
    public const hrresdoc_submodule = [
        'HumanResourceDocumentsManager',
        'HumanResourceDocuments',
    ];
    public const hrhrstate_submodule = [
        'HumanResourceHRStateManager',
        'HumanResourceHRState',
    ];

    public const HR_MODULE = [
        'HumanResourceManager',
        'HumanResource',
        ...self::hremployees_submodule,
        ...self::hreemployees_submodule,
        ...self::hrspreedsheet_submodule,
        ...self::hrresdoc_submodule,
        ...self::hrhrstate_submodule,
    ];

    //INVENTORY
    public const INVENTORY_MODULE = [
        'InventoryManager',
        'Inventory',
        'iproduct_submodule'
    ];

    //PURCHASING
    public const pprovider_submodule = [
        'PurchasingProvidersManager',
        'PurchasingProviders',
    ];
    public const pprequest_submodule = [
        'PurchasingPurchasingRequestManager',
        'PurchasingPurchasingRequest',
    ];
    public const pporder_submodule = [
        'PurchasingPurchasingOrderManager',
        'PurchasingPurchasingOrder',
    ];
    public const ppcpurchase_submodule = [
        'PurchasingCompletedPurchasesManager',
        'PurchasingCompletedPurchases',
    ];
    public const PURCHASING_MODULE = [
        'PurchasingManager',
        'Purchasing',
        ...self::pprovider_submodule,
        ...self::pprequest_submodule,
        ...self::pporder_submodule,
        ...self::ppcpurchase_submodule,
    ];

    //PROJECTS
    public const  pclients_submodule = [
        'ProjectClientsManager',
        'ProjectClients',
    ];
    public const  ppro_submodule = [
        'ProjectPROManager',
        'ProjectPRO',
    ];
    public const pprepint_submodule = [
        'ProjectPreprojectPintManager',
        'ProjectPreprojectPint',
    ];
    public const pprepext_submodule = [
        'ProjectPreprojectPextManager',
        'ProjectPreprojectPext',
    ];
    public const ppropint_submodule = [
        'ProjectProjectPintManager',
        'ProjectProjectPint',
    ];
    public const ppropext_submodule = [
        'ProjectProjectPextManager',
        'ProjectProjectPext',
    ];
    public const pchecklist_submodule = [
        'ProjectChecklistManager',
        'ProjectChecklist',
    ];
    public const pbacklog_submodule = [
        'ProjectBacklogManager',
        'ProjectBacklog',
    ];
    public const padmexpen_submodule = [
        'ProjectAdmExpensesManager',
        'ProjectAdmExpenses',
    ];

    public const PROJECT_MODULE = [
        'ProjectManager',
        'Project',
        ...self::pclients_submodule,
        ...self::ppro_submodule,
        ...self::pprepint_submodule,
        ...self::pprepext_submodule,
        ...self::ppropint_submodule,
        ...self::ppropext_submodule,
        ...self::pchecklist_submodule,
        ...self::pbacklog_submodule,
        ...self::padmexpen_submodule,
    ];

    //FINANCE
    public const fbudget_submodule = [
        'FinanceBudgetManager',
        'FinanceBudget',
    ];
    public const fpapproval_submodule = [
        'FinancePurchaseApprovalManager',
        'FinancePurchaseApproval',
    ];
    public const fdeposists_submodule = [
        'FinanceDepositsManager',
        'FinanceDeposits',
    ];
    public const fpopayment_submodule = [
        'FinancePOPaymentsManager',
        'FinancePOPayments',
    ];
    public const faccstatement_submodule = [
        'FinanceAccountStatementManager',
        'FinanceAccountStatement',
    ];
    public const FINANCE_MODULE = [
        'FinanceManager',
        'Finance',
        ...self::fbudget_submodule,
        ...self::fpapproval_submodule,
        ...self::fdeposists_submodule,
        ...self::fpopayment_submodule,
        ...self::faccstatement_submodule,
    ];

    //HUAWEI and DOCUMENTS
    public const HUAWEI_MODULE = [
        'HuaweiManager'
    ];
    public const DOCUMENT_MODULE = [
        'DocumentGestion'
    ];

    //BILLING
    public const billingpint_submodule = [
        'BillingPintManager',
        'BillingPint',
    ];
    public const billingpext_submodule = [
        'BillingPextManager',
        'BillingPext',
    ];

    public const BILLING_MODULE = [
        'BillingManager',
        'Billing',
        ...self::billingpint_submodule,
        ...self::billingpext_submodule,
    ];

    //CAR
    public const cchanappro_submodule = [
        'CarChangeApprovalManager',
        'CarChangeApproval',
    ];
    public const cmobileunit_submodule = [
        'CarMobileUnitManager',
        'CarMobileUnit',
    ];

    public const CAR_MODULE = [
        'CarManager',
        'Car',
        ...self::cchanappro_submodule,
        ...self::cmobileunit_submodule,
    ];


    // MODULES
    public const MODULES = [
        'USERS_MODULE' => 'USERS_MODULE',
        'HR_MODULE' => 'HR_MODULE',
        'INVENTORY_MODULE' => 'INVENTORY_MODULE',
        'PURCHASING_MODULE' => 'PURCHASING_MODULE',
        'PROJECT_MODULE' => 'PROJECT_MODULE',
        'FINANCE_MODULE' => 'FINANCE_MODULE',
        'BILLING_MODULE' => 'BILLING_MODULE',
        'DOCUMENT_MODULE' => 'DOCUMENT_MODULE',
        'HUAWEI_MODULE' => 'HUAWEI_MODULE',
        'CAR_MODULE' => 'CAR_MODULE',
    ];

    public const SUBMODULES = [
        'user_submodule' => 'user_submodule',
        'roles_submodule' => 'roles_submodule',
        
        'hremployees_submodule' => 'hremployees_submodule',
        'hreemployees_submodule' => 'hreemployees_submodule',
        'hrspreedsheet_submodule' => 'hrspreedsheet_submodule',
        'hrresdoc_submodule' => 'hrresdoc_submodule',
        'hrhrstate_submodule' => 'hrhrstate_submodule',

        'iproduct_submodule' => 'iproduct_submodule',
        
        'pprovider_submodule' => 'pprovider_submodule',
        'pprequest_submodule' => 'pprequest_submodule',
        'pporder_submodule' => 'pporder_submodule',
        'ppcpurchase_submodule' => 'ppcpurchase_submodule',

        'pclients_submodule' => 'pclients_submodule',
        'ppro_submodule' => 'ppro_submodule',
        'pprepint_submodule' => 'pprepint_submodule',
        'pprepext_submodule' => 'pprepext_submodule',
        'ppropint_submodule' => 'ppropint_submodule',
        'ppropext_submodule' => 'ppropext_submodule',
        'pchecklist_submodule' => 'pchecklist_submodule',
        'padmexpen_submodule' => 'padmexpen_submodule',
        'pbacklog_submodule' => 'pbacklog_submodule',

        'fbudget_submodule' => 'fbudget_submodule',
        'fpapproval_submodule' => 'fpapproval_submodule',
        'fdeposists_submodule' => 'fdeposists_submodule',
        'fpopayment_submodule' => 'fpopayment_submodule',
        'faccstatement_submodule' => 'faccstatement_submodule',

        'billingpint_submodule' => 'billingpint_submodule',
        'billingpext_submodule' => 'billingpext_submodule',

        'cchanappro_submodule' => 'cchanappro_submodule',
        'cmobileunit_submodule' => 'cmobileunit_submodule',
    ];
}
