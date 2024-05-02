<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AuthAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/admin/index', [AdminController::class, 'index']),
    Route::post('admin/restore', [AdminController::class, 'restore']),
    Route::delete('admin/delete', [AdminController::class, 'destroy']),
    Route::delete('admin/forceDelete', [AdminController::class, 'forceDelete']),
    Route::put('/admin/{id}/{column}', [AdminController::class, 'toggle']),
    Route::post('/admin-select', [AdminController::class, 'index']),
    Route::post('/admin-logout', [AuthAdminController::class, 'logout']),
    Route::get('/get-admin', [AuthAdminController::class, 'getCurrentAdmin']),
    Route::apiResource('admin', AdminController::class),
]);

Route::post('/admin/login', [AuthAdminController::class, 'login']);

