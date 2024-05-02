<?php

use App\Http\Controllers\Common\ContinentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| continent API Routes
|--------------------------------------------------------------------------
*/


Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/continent/index', [ContinentController::class, 'index']),
    Route::post('continent/restore', [ContinentController::class, 'restore']),
    Route::delete('continent/delete', [ContinentController::class, 'destroy']),
    Route::delete('continent/forceDelete', [ContinentController::class, 'forceDelete']),
    Route::put('/continent/{id}/{column}', [ContinentController::class, 'toggle']),
    Route::apiResource('continent', ContinentController::class),
]);






