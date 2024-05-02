<?php

use App\Http\Controllers\Dashboard\BenefitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Benefit API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/benefit/index', [BenefitController::class, 'index']),
    Route::post('benefit/restore', [BenefitController::class, 'restore']),
    Route::delete('benefit/delete', [BenefitController::class, 'destroy']),
    Route::delete('benefit/forceDelete', [BenefitController::class, 'forceDelete']),
    Route::apiResource('benefit', BenefitController::class),
]);
Route::post('/benefit-select', [BenefitController::class, 'index']);
Route::get('/get-benefit/{slug}', [BenefitController::class, 'publicShow']);

