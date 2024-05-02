<?php

use App\Http\Controllers\Dashboard\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| report API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user-statistic', [ReportController::class, 'userStatistics']);// Get All users (continents, users, users, voted Members)
Route::get('/user-total-count-country', [ReportController::class, 'totalCountPerCountry']);// Get total Count Per Country
Route::post('/user-renewal-member-date', [ReportController::class, 'RenewalMembersByDate']);// Get total renewal member date
Route::post('/companies/count', [ReportController::class, 'companiesCount']);// Get All users (continents, users, users, voted Members)
Route::get('/renewal/dates/count', [ReportController::class, 'renewalUserCount']);// Get All users (continents, users, users, voted Members)
Route::post('/log/index', [ReportController::class, 'logIndex']);// Get All users (continents, users, users, voted Members)


