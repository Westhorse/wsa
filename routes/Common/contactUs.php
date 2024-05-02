<?php

use App\Http\Controllers\Common\ContactUsController;
use App\Http\Controllers\Common\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Media API Routes
|--------------------------------------------------------------------------
*/


Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/contactus/index', [ContactUsController::class, 'index']),
    Route::post('/contactus/restore', [ContactUsController::class, 'restore']),
    Route::delete('/contactus/delete', [ContactUsController::class, 'destroy']),
    Route::apiResource('contactus', ContactUsController::class),
]);
Route::post('/network-send-message', [ContactUsController::class, 'store']);
