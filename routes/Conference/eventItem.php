<?php

use App\Http\Controllers\Conference\EventItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| eventItem API Routes
|--------------------------------------------------------------------------
*/


Route::group(['middleware' => []], fn (): array => [
    Route::post('/event-item/index', [EventItemController::class, 'index']),
    Route::put('/event-item/{id}/{column}', [EventItemController::class, 'toggle']),
    Route::post('event-item/restore', [EventItemController::class, 'restore']),
    Route::delete('event-item/delete', [EventItemController::class, 'destroy']),
    Route::delete('event-item/forceDelete', [EventItemController::class, 'forceDelete']),
    Route::apiResource('event-item', EventItemController::class),
]);
Route::get('/event-item-public', [EventItemController::class, 'indexPublic']);


