<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
/*
|--------------------------------------------------------------------------
| Common API Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/Common/media.php';
require __DIR__.'/Common/certificate.php';
require __DIR__.'/Common/city.php';
require __DIR__.'/Common/country.php';
require __DIR__.'/Common/contactUs.php';
require __DIR__.'/Common/continent.php';
require __DIR__.'/Common/event.php';
require __DIR__.'/Common/faq.php';
require __DIR__.'/Common/incoterm.php';
require __DIR__.'/Common/referral.php';
require __DIR__.'/Common/service.php';
require __DIR__.'/Common/team.php';
require __DIR__.'/Common/testimonial.php';
require __DIR__.'/Common/user.php';

/*
|--------------------------------------------------------------------------
| Dashboard API Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/Dashboard/admin.php';
require __DIR__.'/Dashboard/content.php';
require __DIR__.'/Dashboard/benefit.php';
require __DIR__.'/Dashboard/ref.php';
require __DIR__.'/Dashboard/emailTemplate.php';
require __DIR__.'/Dashboard/group.php';
require __DIR__.'/Dashboard/menu.php';
require __DIR__.'/Dashboard/network.php';
require __DIR__.'/Dashboard/page.php';
require __DIR__.'/Dashboard/news.php';
require __DIR__.'/Dashboard/partner.php';
require __DIR__.'/Dashboard/report.php';
require __DIR__.'/Dashboard/role.php';
require __DIR__.'/Dashboard/setting.php';
require __DIR__.'/Dashboard/slider.php';
require __DIR__.'/Dashboard/vote.php';




/*
|--------------------------------------------------------------------------
| Conference API Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/Conference/conference.php';
require __DIR__.'/Conference/oneToOneMeeting.php';
require __DIR__.'/Conference/coupon.php';
require __DIR__.'/Conference/eventMenu.php';
require __DIR__.'/Conference/eventContactUs.php';
require __DIR__.'/Conference/sponsor.php';
require __DIR__.'/Conference/eventHelpCenter.php';
require __DIR__.'/Conference/eventDay.php';
require __DIR__.'/Conference/program.php';
require __DIR__.'/Conference/timeSlot.php';
require __DIR__.'/Conference/eventPage.php';
require __DIR__.'/Conference/eventItem.php';
require __DIR__.'/Conference/eventSectionPage.php';
require __DIR__.'/Conference/settingEvent.php';
require __DIR__.'/Conference/delegate.php';
require __DIR__.'/Conference/dietary.php';
require __DIR__.'/Conference/order.php';
require __DIR__.'/Conference/spouse.php';
require __DIR__.'/Conference/tshirtSize.php';
require __DIR__.'/Conference/room.php';
require __DIR__.'/Conference/sponsorshipItem.php';
require __DIR__.'/Conference/package.php';
require __DIR__.'/Conference/report.php';
require __DIR__.'/Conference/visit.php';


/* VisitController
|--------------------------------------------------------------------------
| Network API Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/Mobile/page.php';

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/Network/public.php';
require __DIR__.'/Network/auth.php';



