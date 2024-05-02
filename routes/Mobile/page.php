<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\Mobile\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| page API Routes
|--------------------------------------------------------------------------
*/



Route::get('/event-page/{slug}', [PageController::class, 'asShowSlugMobile']);
