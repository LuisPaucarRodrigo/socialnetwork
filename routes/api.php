    <?php

    use App\Http\Controllers\ApiController;
    use Illuminate\Support\Facades\Route;

    Route::post('/login', [ApiController::class, 'login']);

    Route::get('/version/check', [ApiController::class, 'checkVersion']);

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

        //ProcessManuals
        Route::post('/processmanuals/index', [ApiController::class, 'localDriveIndex']);
        Route::post('/processmanuals/folder_archive_download', [ApiController::class, 'localDriveDownload']);

        //CheckList
        Route::get('/constans_checkList/{cost_line_id}', [ApiController::class, 'contantsCheckList']);
        Route::post('/checklistcar', [ApiController::class, 'car_store']);
        Route::post('/checklisttoolkit', [ApiController::class, 'toolkit_store']);
        Route::post('/checklistdailytoolkit', [ApiController::class, 'dailytoolkit_store']);
        Route::post('/checklistepp', [ApiController::class, 'epp_store']);

        Route::get('/vehicle/{cost_line_id}', [ApiController::class, 'index_car']);

        Route::get('/checklistHistory', [ApiController::class, 'checklist_history']);
        Route::post('/expense/store', [ApiController::class, 'expenseStore']);

        Route::get('/expense/index', [ApiController::class, 'expenseIndex']);


        Route::get('/cicsaProcessList/{zone}', [ApiController::class, 'cicsaProcess']);
        Route::post('/expensePext/store', [ApiController::class, 'storeExpensesPext']);
        Route::get('/expensePext/history', [ApiController::class, 'historyExpensesPext']);
        Route::get('/employees/cost_line/{cost_line_id}', [ApiController::class, 'employee_cost_line']);
        //pint constants
        Route::get('/pintconstants/mobile', [ApiController::class, 'getPintMobileConstants']);
        Route::get('/pextconstants/mobile', [ApiController::class, 'getPextMobileConstants']);
        //NUEVAS RUTAS
        Route::get('/huawei/expenses/{macro}/fetch_sites', [ApiController::class, 'fetchSites']);
        Route::get('/huawei/expenses/{macro}/{site_id}/fetch_projects', [ApiController::class, 'fetchProjects']);
        Route::post('/huawei/expenses/storeExpense/post', [ApiController::class, 'storeHuaweiExpense']);
        Route::get('/huawei/expenses/get_constants', [ApiController::class, 'getHuaweiConstants']);
        Route::get('/huawei/expenses/get_history', [ApiController::class, 'getExpensesHistory']);
    });
