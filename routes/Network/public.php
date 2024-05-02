<?php

use App\Http\Controllers\Network\NetworkPublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

Route::get('/continent-public', [NetworkPublicController::class, 'indexPublicContinent']);
Route::get('/country-public', [NetworkPublicController::class, 'indexPublicCountry']);
Route::get('/city-public', [NetworkPublicController::class, 'indexPublicCity']);
Route::get('/certificate-public', [NetworkPublicController::class, 'indexPublicCertificate']);
Route::get('/referral-public', [NetworkPublicController::class, 'indexPublicReferral']);
Route::get('/service-public', [NetworkPublicController::class, 'indexPublicService']);
Route::get('/page-public', [NetworkPublicController::class, 'indexPublicPage']);
Route::get('/page-section-public', [NetworkPublicController::class, 'indexPublicPageSection']);
Route::get('/menu-public', [NetworkPublicController::class, 'indexPublicMenu']);
Route::get('/sub-menu-public', [NetworkPublicController::class, 'indexPublicSubMenu']);
Route::get('/news-public', [NetworkPublicController::class, 'indexPublicNews']);
Route::get('/event-public', [NetworkPublicController::class, 'indexPublicEvent']);
Route::get('/slider-public', [NetworkPublicController::class, 'indexPublicSlider']);
Route::get('/testimonial-public', [NetworkPublicController::class, 'indexPublicTestimonial']);
Route::get('/partner-public', [NetworkPublicController::class, 'indexPublicPartner']);
Route::get('/team-public', [NetworkPublicController::class, 'indexPublicTeam']);
Route::get('/incoterm-public', [NetworkPublicController::class, 'indexPublicIncoterm']);
Route::get('/faq-public', [NetworkPublicController::class, 'indexPublicFaq']);
Route::get('/benefit-public', [NetworkPublicController::class, 'indexPublicBenefit']);
Route::get('/content-public', [NetworkPublicController::class, 'indexPublicContent']);
