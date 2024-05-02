<?php

use App\Http\Controllers\Conference\EventHelpCenterController;
use App\Http\Controllers\Conference\SponsorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sponsor API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/event-help-center/index', [EventHelpCenterController::class, 'index']),
    Route::put('/event-help-center/{id}/{column}', [EventHelpCenterController::class, 'toggle']),
    Route::post('event-help-center/restore', [EventHelpCenterController::class, 'restore']),
    Route::delete('event-help-center/delete', [EventHelpCenterController::class, 'destroy']),
    Route::delete('event-help-center/forceDelete', [EventHelpCenterController::class, 'forceDelete']),
    Route::apiResource('event-help-center', EventHelpCenterController::class),
]);
Route::get('/event-help-center-public', [EventHelpCenterController::class, 'indexPublic']);

