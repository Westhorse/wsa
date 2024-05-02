<?php

use App\Http\Controllers\Conference\TshirtSizeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| tshirt size API Routes
|--------------------------------------------------------------------------
*/



// ---------------------------- Tshirt Sizes ----------------------------

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/tshirt-size/index', [TshirtSizeController::class, 'index']),
    Route::put('/tshirt-size/{id}/{column}', [TshirtSizeController::class, 'toggle']),
    Route::post('tshirt-size/restore', [TshirtSizeController::class, 'restore']),
    Route::delete('tshirt-size/delete', [TshirtSizeController::class, 'destroy']),
    Route::delete('tshirt-size/forceDelete', [TshirtSizeController::class, 'forceDelete']),
    Route::apiResource('tshirt-size', TshirtSizeController::class),
]);
Route::get('/tshirt-size-public', [TshirtSizeController::class, 'indexPublic']);
