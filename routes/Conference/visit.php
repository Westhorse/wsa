<?php

use App\Http\Controllers\Conference\TshirtSizeController;
use App\Http\Controllers\Conference\VisitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| visit size API Routes
|--------------------------------------------------------------------------
*/



// ---------------------------- Tshirt Sizes ----------------------------
// visit API routes
Route::group(['middleware' => []], fn (): array => [
    Route::post('/visit/index', [VisitController::class, 'index']),
    Route::delete('visit/delete', [VisitController::class, 'destroy']),
]);


Route::post('visit/guest/store', [VisitController::class, 'storeGuest']);
Route::group(['middleware' => getAuthMiddleware('user')], fn (): array => [
    Route::post('visit/auth/store', [VisitController::class, 'storeAuth']),
]);
