<?php

use App\Http\Controllers\Conference\SponsorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sponsor API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/sponsor/index', [SponsorController::class, 'index']),
    Route::put('/sponsor/{id}/{column}', [SponsorController::class, 'toggle']),
    Route::post('sponsor/restore', [SponsorController::class, 'restore']),
    Route::delete('sponsor/delete', [SponsorController::class, 'destroy']),
    Route::delete('sponsor/forceDelete', [SponsorController::class, 'forceDelete']),
    Route::apiResource('sponsor', SponsorController::class),
]);
Route::get('/sponsor-public', [SponsorController::class, 'indexPublic']);

