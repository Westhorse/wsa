<?php

use App\Http\Controllers\Conference\DashboardSpouseController;
use App\Http\Controllers\Conference\EventPageController;
use App\Http\Controllers\Conference\SpouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| eventPage API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => []], fn (): array => [
    Route::post('/spouse/index', [SpouseController::class, 'index']),
    Route::put('/spouse/{id}/{column}', [SpouseController::class, 'toggle']),
    Route::post('spouse/restore', [SpouseController::class, 'restore']),
    Route::delete('spouse/delete', [SpouseController::class, 'destroy']),
    Route::delete('spouse/forceDelete', [SpouseController::class, 'forceDelete']),
    Route::apiResource('spouse', SpouseController::class),
]);
Route::get('/spouse-public', [SpouseController::class, 'indexPublic']);


// delegate -> Dashboard API routes
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('dashboard/spouse/index', [DashboardSpouseController::class, 'index']),
    Route::get('dashboard/spouse/{spouse}', [DashboardSpouseController::class, 'show']),
    Route::delete('dashboard/spouse/delete', [DashboardSpouseController::class, 'destroy']),
    Route::patch('dashboard/spouse/{spouse}', [DashboardSpouseController::class, 'update']),

]);
