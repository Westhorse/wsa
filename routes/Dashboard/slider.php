<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\PageSectionController;
use App\Http\Controllers\Dashboard\PartnerController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ReferralController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| setting API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/slider/index', [SliderController::class, 'index']),
    Route::post('slider/restore', [SliderController::class, 'restore']),
    Route::delete('slider/delete', [SliderController::class, 'destroy']),
    Route::delete('slider/forceDelete', [SliderController::class, 'forceDelete']),
    Route::put('/slider/{id}/{column}', [SliderController::class, 'toggle']),
    Route::post('/slider-select', [SliderController::class, 'index']),
    Route::apiResource('slider', SliderController::class),
]);
