    <?php

    use App\Http\Controllers\ConstantController;
    use Illuminate\Support\Facades\Route;

    Route::get('/facturation/{type}', [ConstantController::class, 'facturation'])->name('facturation');
