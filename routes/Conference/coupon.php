<?php

use App\Http\Controllers\Conference\CouponController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Coupon API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/coupon/index', [CouponController::class, 'index']),
    Route::put('/coupon/{id}/{column}', [CouponController::class, 'toggle']),
    Route::post('coupon/restore', [CouponController::class, 'restore']),
    Route::delete('coupon/delete', [CouponController::class, 'destroy']),
    Route::delete('coupon/forceDelete', [CouponController::class, 'forceDelete']),
    Route::apiResource('coupon', CouponController::class),
]);
Route::get('/coupon-public', [CouponController::class, 'indexPublic']);

