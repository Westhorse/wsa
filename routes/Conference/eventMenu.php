<?php

use App\Http\Controllers\Conference\EventMenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Event-menu API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/event-menu/index', [EventMenuController::class, 'index']),
    Route::put('/event-menu/{id}/{column}', [EventMenuController::class, 'toggle']),
    Route::post('event-menu/restore', [EventMenuController::class, 'restore']),
    Route::delete('event-menu/delete', [EventMenuController::class, 'destroy']),
    Route::delete('event-menu/forceDelete', [EventMenuController::class, 'forceDelete']),
    Route::apiResource('event-menu', EventMenuController::class),
]);
Route::get('/event-menu-public', [EventMenuController::class, 'indexPublic']);

