<?php

use App\Http\Controllers\Dashboard\RefController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ref API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/ref/index', [RefController::class, 'index']),
    Route::post('ref/restore', [RefController::class, 'restore']),
    Route::delete('ref/delete', [RefController::class, 'destroy']),
    Route::delete('ref/forceDelete', [RefController::class, 'forceDelete']),
    Route::apiResource('ref', RefController::class),
]);

Route::post('/ref-select', [RefController::class, 'index']);

