<?php

use App\Http\Controllers\Common\ReferralController;
use App\Http\Controllers\Common\ServiceController;
use App\Http\Controllers\Dashboard\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| partner API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('team/index', [TeamController::class, 'index']),
    Route::post('team/restore', [TeamController::class, 'restore']),
    Route::delete('team/delete', [TeamController::class, 'destroy']),
    Route::delete('team/forceDelete', [TeamController::class, 'forceDelete']),
    Route::put('/team/{id}/{column}', [TeamController::class, 'toggle']),
    Route::apiResource('team', TeamController::class),
]);
