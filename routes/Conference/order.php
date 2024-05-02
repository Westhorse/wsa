<?php

use App\Http\Controllers\Conference\ConferenceOrderController;
use App\Http\Controllers\Conference\DashboardOrderController;
use App\Http\Controllers\Conference\DietaryController;
use App\Http\Controllers\Conference\OnlineCheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| dietary API Routes
|--------------------------------------------------------------------------
*/

// ---------------------------- Dietary ----------------------------

Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    // Order API routes
    Route::get('/order/{order}', [ConferenceOrderController::class, 'show']),
    Route::post('/order/{order}/change-status', [ConferenceOrderController::class, 'changOrderStatus']),
    Route::delete('/order/{order}', [ConferenceOrderController::class, 'delete']),
    Route::get('/user/{user}/persons-without-room', [ConferenceOrderController::class, 'personsWithRoom']),
    Route::post('/order/store-order', [ConferenceOrderController::class, 'storeOrder']),

    // Order -> Packages API routes
    Route::patch('/order/{order}/update-package', [ConferenceOrderController::class, 'updateOrderPackage']),

    // Order -> Sponsorship Items & VIP Tables API routes
    Route::patch('/order/{order}/sync-sponsorship-items', [ConferenceOrderController::class, 'syncSponsorshipItem']),

    // Order -> Delegates API routes
    Route::get('/order/{order}/show-delegate/{delegate}', [ConferenceOrderController::class, 'showOrderDelegate']),
    Route::patch('/order/{order}/update-delegate/{delegate}', [ConferenceOrderController::class, 'updateOrderDelegate']),
    Route::post('/order/{order}/add-delegate', [ConferenceOrderController::class, 'addDelegateToOrder']),
    Route::delete('/order/{order}/remove-delegate/{delegate}', [ConferenceOrderController::class, 'removeDelegateFromOrder']),

    // Order -> Spouses API routes
    Route::get('/order/{order}/show-spouse/{spouse}', [ConferenceOrderController::class, 'showOrderSpouse']),
    Route::patch('/order/{order}/update-spouse/{spouse}', [ConferenceOrderController::class, 'updateOrderSpouse']),
    Route::post('/order/{order}/add-spouse', [ConferenceOrderController::class, 'addSpouseToOrder']),
    Route::delete('/order/{order}/remove-spouse/{spouse}', [ConferenceOrderController::class, 'removeSpouseFromOrder']),

    // Order -> Rooms API routes
    Route::post('/order/{order}/add-room', [ConferenceOrderController::class, 'addRoomToOrder']),
    Route::get('/order/{order}/show-room/{bookedRoomID}', [ConferenceOrderController::class, 'showRoomInOrder']),
    Route::patch('/order/{order}/update-room/{bookedRoomID}', [ConferenceOrderController::class, 'updateRoomInOrder']),
    Route::delete('/order/{order}/remove-room/{bookedRoomID}', [ConferenceOrderController::class, 'removeRoomFromOrder']),

    // Order -> Coupon API routes
    Route::post('/order/{order}/add-coupon', [ConferenceOrderController::class, 'addCouponToOrder']),
    Route::delete('/order/{order}/remove-coupon', [ConferenceOrderController::class, 'removeCouponFromOrder']),

]);


// Order -> Dashboard API routes
Route::group(['auth:admins'], fn (): array => [
    Route::post('dashboard/order/index', [DashboardOrderController::class, 'index']),
    Route::get('dashboard/order/{order}', [DashboardOrderController::class, 'show']),
    Route::delete('dashboard/order/{order}/delete', [DashboardOrderController::class, 'delete']),
    Route::post('dashboard/order/{order}/change-status', [DashboardOrderController::class, 'changOrderStatusDashboard']),
    Route::delete('dashboard/conference/member/{user}/delete', [DashboardOrderController::class, 'conferenceMemberDelete']),
]);


Route::post('order/checkout', [OnlineCheckoutController::class, 'checkout']);
Route::post('order/{order}/place-order-details', [OnlineCheckoutController::class, 'validatePaymentIntent']);
