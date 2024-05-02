<?php

use App\Http\Controllers\Conference\SettingEventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| settingEvent API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => []], fn (): array => [
    Route::post('/setting-event/index', [SettingEventController::class, 'index']),
    Route::put('/setting-event/{id}/{column}', [SettingEventController::class, 'toggle']),
    Route::post('setting-event/restore', [SettingEventController::class, 'restore']),
    Route::delete('setting-event/delete', [SettingEventController::class, 'destroy']),
    Route::delete('setting-event/forceDelete', [SettingEventController::class, 'forceDelete']),
    Route::apiResource('setting-event', SettingEventController::class),
    Route::get('/setting-event/sections', [SettingEventController::class, 'settingSectionsList']),
    Route::get('/setting-event/show-section/{setting_event}', [SettingEventController::class, 'showSectionItems']),
    Route::post('/setting-event/section-update', [SettingEventController::class, 'updateSettings']),

]);
Route::post('/setting-event-public', [SettingEventController::class, 'publicIndexSetting']);

