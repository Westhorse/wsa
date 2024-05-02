<?php

use App\Http\Controllers\Common\CityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| City API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/city/index', [CityController::class, 'index']),
    Route::post('city/restore', [CityController::class, 'restore']),
    Route::delete('city/delete', [CityController::class, 'destroy']),
    Route::delete('city/forceDelete', [CityController::class, 'forceDelete']),
    Route::put('/city/{id}/{column}', [CityController::class, 'toggle']),
    Route::apiResource('city', CityController::class),
]);
