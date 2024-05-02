<?php

use App\Http\Controllers\Common\CityController;
use App\Http\Controllers\Common\CountryController;
use App\Http\Controllers\Common\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| event API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/event/index', [EventController::class, 'index']),
    Route::post('event/restore', [EventController::class, 'restore']),
    Route::delete('event/delete', [EventController::class, 'destroy']),
    Route::delete('event/forceDelete', [EventController::class, 'forceDelete']),
    Route::put('/event/{id}/{column}', [EventController::class, 'toggle']),
    Route::post('/event-select', [EventController::class, 'index']),
    Route::apiResource('event', EventController::class),
]);
Route::get('get-event/{slug}', [EventController::class, 'showPublic']);
