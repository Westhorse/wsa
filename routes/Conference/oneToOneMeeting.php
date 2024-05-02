<?php

use App\Http\Controllers\Conference\OneToOneMeetingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| oneToOneMeeting size API Routes
|--------------------------------------------------------------------------
*/



// ---------------------------- oneToOneMeeting ----------------------------

Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    Route::get('/event/schedule-list/{delegate}', [OneToOneMeetingController::class, 'scheduleMeetingList']),
    Route::post('/event/book-time-slot', [OneToOneMeetingController::class, 'bookTimeSlot']),
    Route::get('/event/one-to-one-list', [OneToOneMeetingController::class, 'oneToOneMeetingList']),
    Route::patch('/event/change-time-slot-status/{delegates_time_slots_id}', [OneToOneMeetingController::class, 'changeTimeSlotStatus']),
    Route::patch('/event/cancel-time-slot', [OneToOneMeetingController::class, 'cancelBooking']),
    Route::post('/event/all-delegate', [OneToOneMeetingController::class, 'allDelegateList']),
    Route::post('/event/one-to-one/save-table-number', [OneToOneMeetingController::class, 'oneToOneSaveTableNumber']),
]);
Route::get('pdf/one-to-one/view/{day_id}', [OneToOneMeetingController::class, 'viewPdfOneToOneMeeting']);
Route::get('pdf/one-to-one/download/{day_id}', [OneToOneMeetingController::class, 'downloadPdfOneToOneMeeting']);
