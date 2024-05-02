<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\PageSectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| page API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/page/index', [PageController::class, 'index']),
    Route::post('page/restore', [PageController::class, 'restore']),
    Route::delete('page/delete', [PageController::class, 'destroy']),
    Route::delete('page/forceDelete', [PageController::class, 'forceDelete']),
    Route::put('/page/{id}/{column}', [PageController::class, 'toggle']),
    Route::post('/page-select', [PageController::class, 'index']),
    Route::apiResource('page', PageController::class),
]);
Route::get('/get-page/{page}', [PageController::class, 'publicShow']);


Route::group(['middleware' => []], fn (): array => [
    Route::post('/page-section/index', [PageSectionController::class, 'index']),
    Route::post('page-section/restore', [PageSectionController::class, 'restore']),
    Route::delete('page-section/delete', [PageSectionController::class, 'destroy']),
    Route::delete('page-section/forceDelete', [PageSectionController::class, 'forceDelete']),
    Route::put('/page-section/{id}/{column}', [PageSectionController::class, 'toggle']),
    Route::post('/page-section-select', [PageSectionController::class, 'index']),
    Route::apiResource('page-section', PageSectionController::class),
]);
