<?php


use App\Http\Controllers\Dashboard\GroupController;
use App\Http\Controllers\Dashboard\MenuController;
use App\Http\Controllers\Dashboard\SubMenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| menu API Routes
|--------------------------------------------------------------------------
*/
Route::get('/get-menu/{menu}', [MenuController::class, 'showPublic']);
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/menu/index', [MenuController::class, 'index']),
    Route::post('menu/restore', [MenuController::class, 'restore']),
    Route::delete('menu/delete', [MenuController::class, 'destroy']),
    Route::delete('menu/forceDelete', [MenuController::class, 'forceDelete']),
    Route::put('/menu/{id}/{column}', [MenuController::class, 'toggle']),
    Route::post('/menu-select', [MenuController::class, 'index']),
    Route::apiResource('menu', MenuController::class),
]);

Route::group(['middleware' => []], fn (): array => [
    Route::post('/sub-menu/index', [SubMenuController::class, 'index']),
    Route::post('sub-menu/restore', [SubMenuController::class, 'restore']),
    Route::post('sub-menu/delete/{id}', [SubMenuController::class, 'delete']),
    Route::delete('sub-menu/forceDelete', [SubMenuController::class, 'forceDelete']),
    Route::put('/sub-menu/{id}/{column}', [SubMenuController::class, 'toggle']),
    Route::post('/sub-menu-select', [SubMenuController::class, 'index']),
    Route::apiResource('sub-menu', SubMenuController::class),
]);
