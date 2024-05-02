<?php

use App\Http\Controllers\Conference\DietaryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| dietary API Routes
|--------------------------------------------------------------------------
*/

// ---------------------------- Dietary ----------------------------

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/dietary/index', [DietaryController::class, 'index']),
    Route::put('/dietary/{id}/{column}', [DietaryController::class, 'toggle']),
    Route::post('dietary/restore', [DietaryController::class, 'restore']),
    Route::delete('dietary/delete', [DietaryController::class, 'destroy']),
    Route::delete('dietary/forceDelete', [DietaryController::class, 'forceDelete']),
    Route::apiResource('dietary', DietaryController::class),
]);
Route::get('/dietary-public', [DietaryController::class, 'indexPublic']);

