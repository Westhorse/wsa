<?php

use App\Http\Controllers\Common\CityController;
use App\Http\Controllers\Common\CountryController;
use App\Http\Controllers\Common\EventController;
use App\Http\Controllers\Common\FaqController;
use App\Http\Controllers\Common\IncotermController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| incoterm API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/incoterm/index', [IncotermController::class, 'index']),
    Route::post('incoterm/restore', [IncotermController::class, 'restore']),
    Route::delete('incoterm/delete', [IncotermController::class, 'destroy']),
    Route::delete('incoterm/forceDelete', [IncotermController::class, 'forceDelete']),
    Route::put('/incoterm/{id}/{column}', [IncotermController::class, 'toggle']),
    Route::post('/incoterm-select', [IncotermController::class, 'index']),
    Route::apiResource('incoterm', IncotermController::class),
]);
