<?php

use App\Http\Controllers\Conference\DashboardDelegateController;
use App\Http\Controllers\Conference\DelegateController;
use App\Http\Controllers\Conference\SettingEventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| delegate API Routes
|--------------------------------------------------------------------------
*/

// delegate -> Dashboard API routes
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('dashboard/delegate/index', [DashboardDelegateController::class, 'index']),
    Route::get('dashboard/delegate/{delegate}', [DashboardDelegateController::class, 'show']),
    Route::delete('dashboard/delegate/delete', [DashboardDelegateController::class, 'destroy']),
    Route::patch('dashboard/delegate/{delegate}', [DashboardDelegateController::class, 'update']),
    Route::post('/event/email-reset-password', [DelegateController::class, 'resetPassword']),
]);

Route::group(['middleware' => []], fn (): array => [
    Route::post('/delegate/index', [DelegateController::class, 'index']),
    Route::put('/delegate/{id}/{column}', [DelegateController::class, 'toggle']),
    Route::post('delegate/restore', [DelegateController::class, 'restore']),
    Route::delete('delegate/delete', [DelegateController::class, 'destroy']),
    Route::delete('delegate/forceDelete', [DelegateController::class, 'forceDelete']),
    Route::apiResource('delegate', DelegateController::class),
    Route::post('login/as-delegate/{delegate}', [DelegateController::class, 'loginAsDelegate']),

]);
Route::post('login/event', [DelegateController::class, 'loginEvent']);
Route::get('/delegate-public', [DelegateController::class, 'indexPublic']);
