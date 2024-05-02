<?php

use App\Http\Controllers\Common\ReferralController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| partner API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/referral/index', [ReferralController::class, 'index']),
    Route::post('referral/restore', [ReferralController::class, 'restore']),
    Route::delete('referral/delete', [ReferralController::class, 'destroy']),
    Route::delete('referral/forceDelete', [ReferralController::class, 'forceDelete']),
    Route::put('/referral/{id}/{column}', [ReferralController::class, 'toggle']),
    Route::apiResource('referral', ReferralController::class),
]);


