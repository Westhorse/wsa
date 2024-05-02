<?php


use App\Http\Controllers\Conference\ProgramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| eventDay API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:admins']], fn (): array => [
    Route::post('/program/index', [ProgramController::class, 'index']),
    Route::put('/program/{id}/{column}', [ProgramController::class, 'toggle']),
    Route::post('program/restore', [ProgramController::class, 'restore']),
    Route::delete('program/delete', [ProgramController::class, 'destroy']),
    Route::delete('program/forceDelete', [ProgramController::class, 'forceDelete']),
    Route::apiResource('program', ProgramController::class),
]);
Route::post('/program-public', [ProgramController::class, 'index']);
Route::get('/conference/public/agenda-list', [ProgramController::class, 'publicIndex']);

