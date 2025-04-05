    <?php

    use App\Http\Controllers\ApiController;
    use App\Http\Controllers\ProjectArea\ChecklistsController;
    use Illuminate\Support\Facades\Route;

    Route::post('/login', [ApiController::class, 'login']);


    Route::get('/checklistcar', [ChecklistsController::class, 'car_index']);
    Route::middleware(['auth:sanctum'])->group(function () {
        //Verification of token
        Route::get('/verification_token', [ApiController::class, 'verificationToken']);

        Route::get('/users/{id}', [ApiController::class, 'users']);

        //Preprojects
        Route::get('/preproject/{id}', [ApiController::class, 'preproject']);

        Route::get('/preproject/code/{id}', [ApiController::class, 'preprojectcodephoto']);
        Route::get('/codephotospecific/{id}', [ApiController::class, 'codephotospecific']);
        Route::post('/preprojectimage', [ApiController::class, 'preprojectimage']);
        Route::get('/register/photo/{id}', [ApiController::class, 'registerPhoto']);

        Route::post('/logout', [ApiController::class, 'logout']);

        //ProjectHuawei
        Route::get('/huaweiproject/index', [ApiController::class, 'indexHuaweiProjectGeneral']);
        Route::post('/huaweiproject/store', [ApiController::class, 'storeHuaweiProjectGeneral']);
        Route::get('/huaweiproject/{huawei_project_id}/stages/get', [ApiController::class, 'getStagesPerProject']);
        Route::get('/huaweiproject/sites', [ApiController::class, 'getSites']);
        Route::get('/huaweiproject/{code}/code/get', [ApiController::class, 'getCodesAndProjectCode']);
        Route::post('/huaweiproject/stages/codes/store_image', [ApiController::class, 'storeImagePerCode']);
        Route::get('/huaweiproject/{code}/images/get', [ApiController::class, 'getImageHistoryPerCode']);

        Route::post('/huawei/expenses/fetch_sites', [ApiController::class, 'fetchSites']);
        Route::post('/huawei/expenses/fetch_projects', [ApiController::class, 'fetchProjects']);
        Route::post('/huawei/expenses/storeExpense/post', [ApiController::class, 'storeExpense']);

        //ProcessManuals
        Route::post('/processmanuals/index', [ApiController::class, 'localDriveIndex']);
        Route::post('/processmanuals/folder_archive_download', [ApiController::class, 'localDriveDownload']);

        //CheckList
        Route::post('/checklistcar', [ChecklistsController::class, 'car_store']);
        Route::post('/checklisttoolkit', [ChecklistsController::class, 'toolkit_store']);
        Route::post('/checklistdailytoolkit', [ChecklistsController::class, 'dailytoolkit_store']);
        Route::post('/checklistepp', [ChecklistsController::class, 'epp_store']);

        Route::get('/vehicle/{cost_line_id}', [ApiController::class, 'index_car']);

        Route::get('/checklistHistory', [ChecklistsController::class, 'checklist_history']);
        Route::post('/expense/store', [ChecklistsController::class, 'expenseStore']);

        Route::get('/expense/index', [ChecklistsController::class, 'expenseIndex']);

        Route::get('/cicsaProcessList/{zone}', [ApiController::class, 'cicsaProcess']);
        Route::post('/expensePext/store', [ApiController::class, 'storeExpensesPext']);
        Route::get('/expensePext/history', [ApiController::class, 'historyExpensesPext']);
        Route::get('/employees/cost_line/{cost_line_id}', [ApiController::class, 'employee_cost_line']);
        //pint constants
        Route::get('/pintconstants/mobile', [ApiController::class, 'getPintMobileConstants']);
        Route::get('/pextconstants/mobile', [ApiController::class, 'getPextMobileConstants']);

        Route::get('/constans_checkList/{cost_line_id}', [ApiController::class, 'contantsCheckList']);
    });
