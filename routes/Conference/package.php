<?php

use App\Http\Controllers\Conference\PackageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| package API Routes
|--------------------------------------------------------------------------
*/


// ---------------------------- package ----------------------------


Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/package/index', [PackageController::class, 'index']),
    Route::put('/package/{id}/{column}', [PackageController::class, 'toggle']),
    Route::post('package/restore', [PackageController::class, 'restore']),
    Route::delete('package/delete', [PackageController::class, 'destroy']),
    Route::delete('package/forceDelete', [PackageController::class, 'forceDelete']),
    Route::apiResource('package', PackageController::class),
]);
Route::get('/package-public', [PackageController::class, 'indexPublic']);



