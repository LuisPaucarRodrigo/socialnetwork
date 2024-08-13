    <?php

    use App\Http\Controllers\ApiController;
    use App\Http\Controllers\ProjectArea\ChecklistsController;
    use Illuminate\Support\Facades\Route;

    Route::post('/login', [ApiController::class, 'login']);

    Route::get('/preproject/{id}', [ApiController::class, 'preproject']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/users/{id}', [ApiController::class, 'users']);

        //Preprojects
        
        Route::get('/codephotospecific/{id}', [ApiController::class, 'codephotospecific']);
        Route::post('/preprojectimage', [ApiController::class, 'preprojectimage']);
        Route::get('/preproject/code/{id}', [ApiController::class, 'preprojectcodephoto']);
        Route::get('/register/photo/{id}', [ApiController::class, 'registerPhoto']);

        //Project
        Route::get('/project', [ApiController::class, 'project_index']);
        Route::get('/project/show/{id}', [ApiController::class, 'project_show']);
        Route::post('/project/store/image', [ApiController::class, 'project_store_image']);

        Route::post('/logout', [ApiController::class, 'logout']);

        //ProjectHuawei
        Route::get('/huaweiproject/index', [ApiController::class, 'indexHuaweiProjectGeneral']);
        Route::post('/huaweiproject/store', [ApiController::class, 'storeHuaweiProjectGeneral']);

        //ProcessManuals
        Route::post('/processmanuals/index', [ApiController::class, 'localDriveIndex']);
        Route::post('/processmanuals/folder_archive_download', [ApiController::class, 'localDriveDownload']);






        Route::post('checklistcar', [ChecklistsController::class, 'car_store']);
        Route::post('checklisttoolkit', [ChecklistsController::class, 'toolkit_store']);
        Route::post('checklistdailytoolkit', [ChecklistsController::class, 'dailytoolkit_store']);
        Route::post('checklistepp', [ChecklistsController::class, 'epp_store']);

        Route::get('checklistcar', [ChecklistsController::class, 'car_index']);

    });
