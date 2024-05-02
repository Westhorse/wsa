<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/mail-template/cancel-one-to-one/show-example', function () {
    return view('mail.showExample.CancelOneToOneShowExample');
});

Route::get('/mail-template/event-application/show-example', function () {
    return view('mail.showExample.EventApplicationShowExample');
});

Route::get('/mail-template/status-approved/show-example', function () {
    return view('mail.showExample.EventCompanyStatusApprovedShowExample');
});

Route::get('/mail-template/delegate-approved/show-example', function () {
    return view('mail.showExample.DelegateApprovedShowExample');
});

Route::get('/mail-template/contact-us/show-example', function () {
    return view('mail.showExample.EventContactUsShowExample');
});

Route::get('/mail-template/reset-password/show-example', function () {
    return view('mail.showExample.ResetPasswordShowExample');
});

Route::get('/mail-template/receiver-mail/show-example', function () {
    return view('mail.showExample.ReceiverOneToOneMailShowExample');
});

Route::get('/mail-template/sender-mail/show-example', function () {
    return view('mail.showExample.SenderOneToOneMailShowExample');
});

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
