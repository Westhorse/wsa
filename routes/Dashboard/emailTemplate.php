<?php

use App\Http\Controllers\Common\EmailTemplateController;
use App\Http\Controllers\Dashboard\BenefitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| emailtemplate API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/emailtemplate/index', [EmailTemplateController::class, 'index']),
    Route::post('emailtemplate/restore', [EmailTemplateController::class, 'restore']),
    Route::delete('emailtemplate/delete', [EmailTemplateController::class, 'destroy']),
    Route::delete('emailtemplate/forceDelete', [EmailTemplateController::class, 'forceDelete']),
    Route::apiResource('emailtemplate', EmailTemplateController::class),
]);
