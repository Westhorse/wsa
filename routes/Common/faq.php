<?php

use App\Http\Controllers\Common\CityController;
use App\Http\Controllers\Common\CountryController;
use App\Http\Controllers\Common\EventController;
use App\Http\Controllers\Common\FaqController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| faq API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/faq/index', [FaqController::class, 'index']),
    Route::post('faq/restore', [FaqController::class, 'restore']),
    Route::delete('faq/delete', [FaqController::class, 'destroy']),
    Route::delete('faq/forceDelete', [FaqController::class, 'forceDelete']),
    Route::post('/faq-select', [FaqController::class, 'index']),
    Route::apiResource('faq', FaqController::class),
]);
