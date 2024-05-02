<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\NewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/news/index', [NewsController::class, 'index']),
    Route::post('news/restore', [NewsController::class, 'restore']),
    Route::delete('news/delete', [NewsController::class, 'destroy']),
    Route::delete('news/forceDelete', [NewsController::class, 'forceDelete']),
    Route::put('/news/{id}/{column}', [NewsController::class, 'toggle']),
    Route::post('/news-select', [NewsController::class, 'index']),
    Route::apiResource('news', NewsController::class),
]);
Route::get('get-article/{slug}', [NewsController::class, 'showPublic']);
