<?php


use App\Http\Controllers\Conference\TimeSlotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| timeSlot API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/time-slot/index', [TimeSlotController::class, 'index']),
    Route::put('/time-slot/{id}/{column}', [TimeSlotController::class, 'toggle']),
    Route::post('time-slot/restore', [TimeSlotController::class, 'restore']),
    Route::delete('time-slot/delete', [TimeSlotController::class, 'destroy']),
    Route::delete('time-slot/forceDelete', [TimeSlotController::class, 'forceDelete']),
    Route::apiResource('time-slot', TimeSlotController::class),
]);
Route::get('/time-slot-public', [TimeSlotController::class, 'indexPublic']);

