<?php

use App\Http\Controllers\Conference\DelegateController;
use App\Http\Controllers\Conference\OneToOneMeetingController;
use App\Http\Controllers\Conference\ReportController;
use App\Http\Controllers\Conference\TshirtSizeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| report size API Routes
|--------------------------------------------------------------------------
*/



// ---------------------------- Tshirt Sizes ----------------------------

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::get('/event/one-to-one/report', [ReportController::class, 'oneToOneReport']),
    Route::get('/event/room/report', [ReportController::class, 'roomReport']),
    Route::get('/event/dashboard/report', [ReportController::class, 'dashboardReport']),
    Route::get('/event/order/count-per-month', [ReportController::class, 'PaymentsCountPerMonth']),
    Route::get('/event/payment/chart-per-month', [ReportController::class, 'PaymentsChartPerMonth']),
    Route::get('/event/count/visit-country', [ReportController::class, 'countVisitCountry']),
    Route::get('/event/dietary-delegate/report', [ReportController::class, 'getDietaryUsersReport']),
    Route::get('/event/tshirt-sizes/report', [ReportController::class, 'getTshirtSizesReport']),
    Route::post('/event/email-reset-password', [DelegateController::class, 'resetPassword']),
]);
