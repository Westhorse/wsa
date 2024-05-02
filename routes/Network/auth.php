<?php

use App\Http\Controllers\Common\NetworkUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| auth API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:sanctum']], fn (): array => [
    Route::post('network/vote/member', [NetworkUserController::class, 'addVoting']), // User Vote Method
]);
