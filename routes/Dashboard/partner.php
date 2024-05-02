<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\PageSectionController;
use App\Http\Controllers\Dashboard\PartnerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| partner API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/partner/index', [PartnerController::class, 'index']),
    Route::post('partner/restore', [PartnerController::class, 'restore']),
    Route::delete('partner/delete', [PartnerController::class, 'destroy']),
    Route::delete('partner/forceDelete', [PartnerController::class, 'forceDelete']),
    Route::put('/partner/{id}/{column}', [PartnerController::class, 'toggle']),
    Route::post('/partner-select', [PartnerController::class, 'index']),
    Route::apiResource('partner', PartnerController::class),
]);


