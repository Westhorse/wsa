<?php

use App\Http\Controllers\Common\ReferralController;
use App\Http\Controllers\Common\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| partner API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/service/index', [ServiceController::class, 'index']),
    Route::post('service/restore', [ServiceController::class, 'restore']),
    Route::delete('service/delete', [ServiceController::class, 'destroy']),
    Route::delete('service/forceDelete', [ServiceController::class, 'forceDelete']),
    Route::put('/service/{id}/{column}', [ServiceController::class, 'toggle']),
    Route::apiResource('service', ServiceController::class),
]);


