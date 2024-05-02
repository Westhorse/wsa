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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| role API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/role/index', [RoleController::class, 'index']),
    Route::post('role/restore', [RoleController::class, 'restore']),
    Route::delete('role/delete', [RoleController::class, 'destroy']),
    Route::delete('role/forceDelete', [RoleController::class, 'forceDelete']),
    Route::apiResource('role', RoleController::class),
    Route::post('/permission/index', [PermissionController::class, 'index']),
    Route::apiResource('permission', PermissionController::class),
]);
Route::get('admin-permissions/{id}', [RoleController::class, 'adminPermissions']);
