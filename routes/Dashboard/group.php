<?php


use App\Http\Controllers\Dashboard\GroupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| group API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('group/restore', [GroupController::class, 'restore']),
    Route::delete('group/forceDelete', [GroupController::class, 'forceDelete']),
    Route::post('/group/index', [GroupController::class, 'index']),
    Route::delete('group/delete', [GroupController::class, 'destroy']),
    Route::apiResource('group', GroupController::class),
]);
Route::post('/group-select', [GroupController::class, 'index']);
