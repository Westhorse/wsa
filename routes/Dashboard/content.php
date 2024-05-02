<?php

use App\Http\Controllers\Dashboard\ContentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Content API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/content/index', [ContentController::class, 'index']),
    Route::post('content/restore', [ContentController::class, 'restore']),
    Route::post('content/delete/{id}', [ContentController::class, 'delete']),
    Route::delete('content/forceDelete', [ContentController::class, 'forceDelete']),
    Route::put('/content/{id}/{column}', [ContentController::class, 'toggle']),
    Route::post('/content-select', [ContentController::class, 'index']),
    Route::apiResource('content', ContentController::class),
]);

