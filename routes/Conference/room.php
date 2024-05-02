<?php

use App\Http\Controllers\Conference\DietaryController;
use App\Http\Controllers\Conference\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| room API Routes
|--------------------------------------------------------------------------
*/

// ---------------------------- room ----------------------------

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/room/index', [RoomController::class, 'index']),
    Route::put('/room/{id}/{column}', [RoomController::class, 'toggle']),
    Route::post('room/restore', [RoomController::class, 'restore']),
    Route::delete('room/delete', [RoomController::class, 'destroy']),
    Route::delete('room/forceDelete', [RoomController::class, 'forceDelete']),
    Route::apiResource('room', RoomController::class),
]);
Route::get('/room-public', [RoomController::class, 'indexPublic']);



