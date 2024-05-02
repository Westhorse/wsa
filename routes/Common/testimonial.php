<?php

use App\Http\Controllers\Common\ReferralController;
use App\Http\Controllers\Common\ServiceController;
use App\Http\Controllers\Dashboard\TeamController;
use App\Http\Controllers\Dashboard\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| partner API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/testimonial/index', [TestimonialController::class, 'index']),
    Route::post('testimonial/restore', [TestimonialController::class, 'restore']),
    Route::delete('testimonial/delete', [TestimonialController::class, 'destroy']),
    Route::delete('testimonial/forceDelete', [TestimonialController::class, 'forceDelete']),
    Route::put('/testimonial/{id}/{column}', [TestimonialController::class, 'toggle']),
    Route::post('/testimonial-select', [TestimonialController::class, 'index']),
    Route::apiResource('testimonial', TestimonialController::class),
]);
