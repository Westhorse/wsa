<?php

use App\Http\Controllers\Conference\RoomController;
use App\Http\Controllers\Conference\SponsorshipItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| sponsorshipItem API Routes
|--------------------------------------------------------------------------
*/


// ---------------------------- sponsorship_item ----------------------------


Route::group(['middleware' => []], fn (): array => [
    Route::post('/sponsorship-item/index', [SponsorshipItemController::class, 'index']),
    Route::put('/sponsorship-item/{id}/{column}', [SponsorshipItemController::class, 'toggle']),
    Route::post('sponsorship-item/restore', [SponsorshipItemController::class, 'restore']),
    Route::delete('sponsorship-item/delete', [SponsorshipItemController::class, 'destroy']),
    Route::delete('sponsorship-item/forceDelete', [SponsorshipItemController::class, 'forceDelete']),
    Route::apiResource('sponsorship-item', SponsorshipItemController::class),
]);
Route::get('/sponsorship-item-public', [SponsorshipItemController::class, 'indexPublic']);



