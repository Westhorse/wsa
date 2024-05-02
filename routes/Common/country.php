<?php

use App\Http\Controllers\Common\CityController;
use App\Http\Controllers\Common\CountryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| country API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/country/index', [CountryController::class, 'index']),
    Route::post('country/restore', [CountryController::class, 'restore']),
    Route::delete('country/delete', [CountryController::class, 'destroy']),
    Route::delete('country/forceDelete', [CountryController::class, 'forceDelete']),
    Route::put('/country/{id}/{column}', [CountryController::class, 'toggle']),
    Route::apiResource('country', CountryController::class),
]);

