<?php

use App\Http\Controllers\Conference\EventSectionPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| eventSectionPage API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => []], fn (): array => [
    Route::post('/event-section-page/index', [EventSectionPageController::class, 'index']),
    Route::put('/event-section-page/{id}/{column}', [EventSectionPageController::class, 'toggle']),
    Route::post('event-section-page/restore', [EventSectionPageController::class, 'restore']),
    Route::delete('event-section-page/delete', [EventSectionPageController::class, 'destroy']),
    Route::delete('event-section-page/forceDelete', [EventSectionPageController::class, 'forceDelete']),
    Route::apiResource('event-section-page', EventSectionPageController::class),
]);
Route::get('/event-section-page-public', [EventSectionPageController::class, 'indexPublic']);


