<?php

use App\Http\Controllers\Common\CertificateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Certificate API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/certificate/index', [CertificateController::class, 'index']),
    Route::post('certificate/restore', [CertificateController::class, 'restore']),
    Route::delete('certificate/delete', [CertificateController::class, 'destroy']),
    Route::delete('certificate/forceDelete', [CertificateController::class, 'forceDelete']),
    Route::put('/certificate/{id}/{column}', [CertificateController::class, 'toggle']),
    Route::apiResource('certificate', CertificateController::class),
]);
