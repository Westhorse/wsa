<?php

use App\Http\Controllers\Conference\EventDayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| eventDay API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/event-day/index', [EventDayController::class, 'index']),
    Route::put('/event-day/{id}/{column}', [EventDayController::class, 'toggle']),
    Route::post('event-day/restore', [EventDayController::class, 'restore']),
    Route::delete('event-day/delete', [EventDayController::class, 'destroy']),
    Route::delete('event-day/forceDelete', [EventDayController::class, 'forceDelete']),
    Route::apiResource('event-day', EventDayController::class),
]);
Route::get('/event-day-public', [EventDayController::class, 'indexPublic']);

