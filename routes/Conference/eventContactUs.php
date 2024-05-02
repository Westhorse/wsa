<?php

use App\Http\Controllers\Conference\EventContactUsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Event Contact Us API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => []], fn (): array => [
    Route::post('/event-contact-us/index', [EventContactUsController::class, 'index']),
    Route::put('/event-contact-us/{id}/{column}', [EventContactUsController::class, 'toggle']),
    Route::post('event-contact-us/restore', [EventContactUsController::class, 'restore']),
    Route::delete('event-contact-us/delete', [EventContactUsController::class, 'destroy']),
    Route::delete('event-contact-us/forceDelete', [EventContactUsController::class, 'forceDelete']),
    Route::apiResource('event-contact-us', EventContactUsController::class),
]);
Route::post('/event-contact-us-public', [EventContactUsController::class, 'store']);

