<?php

use App\Http\Controllers\Conference\EventPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| eventPage API Routes
|--------------------------------------------------------------------------
*/

Route::group([], fn (): array => [
    Route::post('/event-page/index', [EventPageController::class, 'index']),
    Route::put('/event-page/{id}/{column}', [EventPageController::class, 'toggle']),
    Route::post('event-page/restore', [EventPageController::class, 'restore']),
    Route::delete('event-page/delete', [EventPageController::class, 'destroy']),
    Route::delete('event-page/forceDelete', [EventPageController::class, 'forceDelete']),
    Route::apiResource('event-page', EventPageController::class),
    Route::get('/event-page-slug/{slug}', [EventPageController::class, 'showSlug'])
]);
Route::get('/event-page-public', [EventPageController::class, 'indexPublic']);
Route::get('/event-public-page-slug/{slug}', [EventPageController::class, 'publicShowSlug']);

