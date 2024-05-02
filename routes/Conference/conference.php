<?php

use App\Http\Controllers\Conference\ConferenceController;
use App\Http\Controllers\Conference\EventMemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Conference API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/conference/index', [ConferenceController::class, 'index']),
    Route::put('/conference/{id}/{column}', [ConferenceController::class, 'toggle']),
    Route::post('conference/restore', [ConferenceController::class, 'restore']),
    Route::delete('conference/delete', [ConferenceController::class, 'destroy']),
    Route::delete('conference/forceDelete', [ConferenceController::class, 'forceDelete']),
    Route::apiResource('conference', ConferenceController::class),
]);
Route::get('event/{user}/get-all-order', [ConferenceController::class, 'getAllOrder']);
Route::get('event/{user}/get-all-sponsorshipItem', [ConferenceController::class, 'getAllSponsorshipItem']);
Route::get('event/{user}/get-all-room', [ConferenceController::class, 'getAllRoom']);
Route::get('event/{user}/get-all-person', [ConferenceController::class, 'getAllPerson']);

Route::get('/conference-public', [ConferenceController::class, 'indexPublic']);
Route::get('/conference-current', [ConferenceController::class, 'getCurrentConference']);
// Get The Application Form Resources such as [sponsorshipItems,packages,tshirtSizes,dietaries,rooms]
Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    Route::get('/application-form/order-resources-data', [ConferenceController::class, 'getApplicationFormResourcesData']),
]);


Route::post('store/event_non_member', [EventMemberController::class, 'storeEventNonMember']);
Route::post('dashboard/event/all-member/index', [EventMemberController::class, 'indexAllEventMember']);
Route::get('dashboard/member/{user}', [EventMemberController::class, 'show']);
Route::patch('dashboard/member/update/{user}', [EventMemberController::class, 'updateDashboardMember']);

Route::post('login/as-company/{user}', [EventMemberController::class, 'loginAsCompany']);


Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    Route::get('event-auth', [EventMemberController::class, 'getUserEvent']),
    Route::get('conference/member/get-profile', [EventMemberController::class, 'getEventUserProfile']),
    Route::get('conference/delegate/get-profile', [EventMemberController::class, 'getEventDelegateProfile']),
    Route::patch('conference/member/update/{user}', [EventMemberController::class, 'updateMember'])
]);
