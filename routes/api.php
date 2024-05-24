    <?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/login', [ApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users', [ApiController::class, 'users']);

    //Preprojects
    Route::get('/preproject', [ApiController::class, 'preproject']);
    Route::get('/codephotospecific/{id}', [ApiController::class, 'codephotospecific']);
    Route::post('/preprojectimage', [ApiController::class, 'preprojectimage']);
    Route::get('/preproject/code/{id}', [ApiController::class, 'preprojectcodephoto']);
    Route::get('/register/photo/{id}', [ApiController::class, 'registerPhoto']);

    //Project
    Route::get('/project',[ApiController::class,'project_index']);
    Route::get('/project/show/{id}', [ApiController::class, 'project_show']);
    Route::post('/project/store/image',[ApiController::class,'project_store_image']);

    Route::post('/logout', [ApiController::class, 'logout']);
});
