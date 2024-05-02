<?php

use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\VoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| --------------------------- Voting API Routes ---------------------------
|--------------------------------------------------------------------------
*/

// Voting Dashboard Actions
Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::get('network-report/vote/statistics', [VoteController::class, 'voteStatistics']), // Get All Statistics (continents, votes, voters, voted Members)
    Route::get('/network-report/vote/top', [VoteController::class, 'getTopVotedMembersPerContinent']), // Get Top Voted Member in Each Continent
    Route::post('/network-report/vote/update-members-status', [VoteController::class, 'updateVotingActiveStatus']), // Update voting_active Status for Users
    Route::get('/network-report/vote/member/{user_id}', [VoteController::class, 'getUserVote']), // Get Voted Members for User ID using params -> user_id={userId}
]);

// Voting Member Actions
Route::group(['middleware' => []], fn (): array => [
    Route::post('network/vote/member', [VoteController::class, 'addVoting']), // User Vote Method
]);
Route::get('network-report/vote/country/{country_id}', [VoteController::class, 'getUser']); // Get Users By Continent
