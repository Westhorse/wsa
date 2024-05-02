<?php

use App\Http\Controllers\Dashboard\NetworkController;
use App\Http\Controllers\Dashboard\RefController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Network API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/network/index', [NetworkController::class, 'index']),
    Route::post('network/restore', [NetworkController::class, 'restore']),
    Route::delete('network/delete', [NetworkController::class, 'destroy']),
    Route::delete('network/forceDelete', [NetworkController::class, 'forceDelete']),
    Route::put('/network/{id}/{column}', [NetworkController::class, 'toggle']),
    Route::post('/network-select', [NetworkController::class, 'index']),
Route::apiResource('network', NetworkController::class),
]);
Route::post('/network-public', [NetworkController::class, 'index']);
Route::get('/network-domain', [NetworkController::class, 'getNetworkByDomain']);
Route::get('/current-network', [NetworkController::class, 'getNetworkData']);
Route::get('/current-network-data/{network}', [NetworkController::class, 'show']);
Route::get('/get-default-network', [NetworkController::class, 'getDefaultNetwork']);
