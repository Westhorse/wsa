<?php

use App\Http\Controllers\Common\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Media API Routes
|--------------------------------------------------------------------------
*/

Route::controller(MediaController::class)->group(function () {
    Route::get('/media', 'index');
    Route::post('/media', 'store');
    Route::get('/media/{id}', 'getImg');
    Route::delete('/media/{id}', 'deleteImage');
    Route::post('/media-array', 'showMedia');
});
