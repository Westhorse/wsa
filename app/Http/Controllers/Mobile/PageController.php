<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\PageHotelMobileResource;
use App\Http\Resources\Mobile\PageMeetingMobileResource;
use App\Http\Resources\Mobile\PageMobileAboutResource;
use App\Models\EventPage;
use App\Models\EventSectionPage;
use Exception;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function asShowSlugMobile($slug, Request $request)
    {
        try {
            $name = null;
            // about
            if ($slug == 'about') {
                $event_page = EventSectionPage::where('slug', 'home_about')->first();
                $eventPageResource = new PageMobileAboutResource($event_page);
            }
            // hotel
            elseif ($slug == 'hotel') {
                $event_page = EventPage::where('slug', 'hotel')->first();
                $eventPageResource = new PageHotelMobileResource($event_page);
            }
            // contact
            elseif ($slug == 'contact') {
                $event_page = EventPage::where('slug', 'contact')->first();
                $eventPageResource = new PageHotelMobileResource($event_page);
            }
            // one-to-one
            elseif ($slug == 'one-to-one') {
                $event_page = EventPage::where('slug', 'agenda')->first();
                $name = 'One To One Meetings List';
                $eventPageResource = new PageMeetingMobileResource($event_page, $name);
            }
            //agenda
            elseif ($slug == 'agenda') {
                $event_page = EventPage::where('slug', 'agenda')->first();
                $name = 'Agenda';
                $eventPageResource = new PageMeetingMobileResource($event_page, $name);
            }
            //schedule-your-meetings
            elseif ($slug == 'schedule-your-meetings') {
                $event_page = EventPage::where('name', 'Agenda')->first();
                $name = 'Schedule Your Meetings';
                $eventPageResource = new PageMeetingMobileResource($event_page, $name);
            } else {
                return JsonResponse::respondError('Event Page not found');
            }
            return $eventPageResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
