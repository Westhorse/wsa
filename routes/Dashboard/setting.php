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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| setting API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/setting/index', [SettingController::class, 'index']),
    Route::post('setting/restore', [SettingController::class, 'restore']),
    Route::delete('setting/delete', [SettingController::class, 'destroy']),
    Route::delete('setting/forceDelete', [SettingController::class, 'forceDelete']),
    Route::post('/setting/section-update', [SettingController::class, 'updateSettings']),
    Route::get('/setting/sections', [SettingController::class, 'settingSectionsList']),
    Route::apiResource('setting', SettingController::class),
]);
Route::post('/public-setting', [SettingController::class, 'settingPublic']);
