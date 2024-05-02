<?php

use App\Http\Controllers\Common\ContactPersonController;
use App\Http\Controllers\Common\FollowingCaseController;
use App\Http\Controllers\Common\ReferralController;
use App\Http\Controllers\Common\ServiceController;
use App\Http\Controllers\Common\TradeReferenceController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Dashboard\TeamController;
use App\Http\Controllers\Dashboard\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| partner API Routes
|--------------------------------------------------------------------------
*/

Route::get('user/log', [UserController::class, 'log']);


// Members Data for Dashboard
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('user/index', [UserController::class, 'index']), // Done for All Index types
    Route::post('user/restore', [UserController::class, 'restore']),
    Route::delete('user/delete', [UserController::class, 'destroy']),
    Route::delete('user/forceDelete', [UserController::class, 'forceDelete']),
    Route::post('user-select', [UserController::class, 'index']),
    Route::post('user-application-dashboard', [UserController::class, 'createApplication']),
    Route::post('email-reset-password', [UserController::class, 'resetPassword']),
    Route::post('email-approved', [UserController::class, 'emailApproved']),
    Route::patch('user/update-active-network/{id}', [UserController::class, 'updateActiveNetwork']),
    Route::get('user/get-active-network/{id}', [UserController::class, 'getActiveNetwork']),

    Route::get('user/{user}', [UserController::class, 'show']),
    Route::post('get/member', [UserController::class, 'getUsers']), // Get members for Dropdown List
    Route::patch('user/{user}', [UserController::class, 'update']),
]);
Route::post('user', [UserController::class, 'store']);

Route::post('member/blackList', [UserController::class, 'blackList']); // Done for All Index types
// Contact people For Dashboard
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('contact-person/restore', [ContactPersonController::class, 'restore']),
    Route::delete('contact-person/forceDelete', [ContactPersonController::class, 'forceDelete']),
    Route::post('contact-person/index', [ContactPersonController::class, 'index']),
    Route::delete('contact-person/delete', [ContactPersonController::class, 'destroy']),
    Route::apiResource('contact-person', ContactPersonController::class),
]);

// Trade References for Dashboard
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('trade-reference/restore', [TradeReferenceController::class, 'restore']),
    Route::delete('trade-reference/forceDelete', [TradeReferenceController::class, 'forceDelete']),
    Route::post('trade-reference/index', [TradeReferenceController::class, 'index']),
    Route::delete('trade-reference/delete', [TradeReferenceController::class, 'destroy']),
    Route::apiResource('trade-reference', TradeReferenceController::class),
]);

// Member Data for MemberArea
Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    Route::post('member/index', [UserController::class, 'index']),
    Route::patch('member/{user}', [UserController::class, 'memberUpdate']),
    Route::get('get-user', [UserController::class, 'getDashUser']),
    Route::post('member/logout', [UserController::class, 'logoutCompany']),
//    Route::post('member/map-data', [UserController::class, 'getMapMembers']),
    Route::patch('member/update-active-network/{id}', [UserController::class, 'updateActiveNetwork']),
    Route::get('member/show/{wsaId}', [UserController::class, 'showMember']),
    Route::get('member/{user}/show', [UserController::class, 'show']),

]);
Route::post('member/login', [UserController::class, 'loginCompany']);
Route::post('ip', [UserController::class, 'ip']);
Route::post('user/apply', [UserController::class, 'store']);




// Contact people For MemberArea
Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    Route::delete('member/contact-person/forceDelete', [ContactPersonController::class, 'forceDelete']),
    Route::delete('member/contact-person/delete', [ContactPersonController::class, 'destroy']),
    Route::put('member/contact-person/{contact_person}', [ContactPersonController::class, 'update']),
    Route::post('member/contact-person/add', [ContactPersonController::class, 'store']),
    Route::get('member/contact-person/{contact_person}', [ContactPersonController::class, 'show']),
]);


// Following Cases for Admins Uses Only
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('following-case/restore', [FollowingCaseController::class, 'restore']),
    Route::delete('following-case/forceDelete', [FollowingCaseController::class, 'forceDelete']),
    Route::post('following-case/index', [FollowingCaseController::class, 'index']),
    Route::delete('following-case/delete', [FollowingCaseController::class, 'destroy']),
    Route::apiResource('following-case', FollowingCaseController::class),
]);


