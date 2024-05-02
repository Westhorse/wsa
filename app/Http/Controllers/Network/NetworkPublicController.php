<?php

namespace App\Http\Controllers\Network;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Common\CertificateResource;
use App\Http\Resources\Common\CityResource;
use App\Http\Resources\Common\ContinentResource;
use App\Http\Resources\Common\CountryResource;
use App\Http\Resources\Common\EventResource;
use App\Http\Resources\Common\FaqResource;
use App\Http\Resources\Common\IncotermResource;
use App\Http\Resources\Common\ServiceResource;
use App\Http\Resources\Common\TeamResource;
use App\Http\Resources\Dashboard\BenefitResource;
use App\Http\Resources\Dashboard\ContentResource;
use App\Http\Resources\Dashboard\MenuResource;
use App\Http\Resources\Dashboard\NewsResource;
use App\Http\Resources\Dashboard\PageResource;
use App\Http\Resources\Dashboard\PageSectionResource;
use App\Http\Resources\Dashboard\PartnerResource;
use App\Http\Resources\Dashboard\SliderResource;
use App\Http\Resources\Dashboard\SubMenuResource;
use App\Http\Resources\Dashboard\TestimonialResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Benefit;
use App\Models\Certificate;
use App\Models\City;
use App\Models\Content;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Incoterm;
use App\Models\Menu;
use App\Models\News;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Partner;
use App\Models\Referral;
use App\Models\Service;
use App\Models\Slider;
use App\Models\SubMenu;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Exception;
use Request;

class NetworkPublicController extends BaseController
{
    public function indexPublicContinent()
    {
        try {
            $ContinentData = Continent::where('active', 1)->get();
            $Continent = ContinentResource::collection($ContinentData);
            return $Continent->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicCountry()
    {
        try {
            $CountryData = Country::where('active', 1)->get();
            $Country = CountryResource::collection($CountryData);
            return $Country->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicCity()
    {
        try {
            $CityData = City::where('active', 1)->get();
            $City = CityResource::collection($CityData);
            return $City->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicCertificate()
    {
        try {
            $CertificateData = Certificate::where('active', 1)->get();
            $Certificate = CertificateResource::collection($CertificateData);
            return $Certificate->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function indexPublicReferral()
    {
        try {
            $ReferralData = Referral::where('active', 1)->get();
            $Referral = CertificateResource::collection($ReferralData);
            return $Referral->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicService()
    {
        try {
            $ServiceData = Service::where('active', 1)->get();
            $Service = ServiceResource::collection($ServiceData);
            return $Service->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicPage()
    {
        try {
            $PageData = Page::where('active', 1)->get();
            $Page = PageResource::collection($PageData);
            return $Page->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicPageSection()
    {
        try {
            $PageSectionData = PageSection::where('active', 1)->get();
            $PageSection = PageSectionResource::collection($PageSectionData);
            return $PageSection->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicMenu()
    {
        try {
            $MenuData = Menu::where('active', 1)->get();
            $Menu = MenuResource::collection($MenuData);
            return $Menu->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function indexPublicSubMenu()
    {
        try {
            $SubMenuData = SubMenu::where('active', 1)->get();
            $SubMenu = SubMenuResource::collection($SubMenuData);
            return $SubMenu->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicNews()
    {
        try {
            $NewsData = News::where('active', 1)->get();
            $News = NewsResource::collection($NewsData);
            return $News->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicEvent()
    {
        try {
            $EventData = Event::where('active', 1)->get();
            $Event = EventResource::collection($EventData);
            return $Event->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicSlider()
    {
        try {
            $SliderData = Slider::where('active', 1)->get();
            $Slider = SliderResource::collection($SliderData);
            return $Slider->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicTestimonial()
    {
        try {
            $TestimonialData = Testimonial::where('active', 1)->get();
            $Testimonial = TestimonialResource::collection($TestimonialData);
            return $Testimonial->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicPartner()
    {
        try {
            $PartnerData = Partner::where('active', 1)->get();
            $Partner = PartnerResource::collection($PartnerData);
            return $Partner->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicTeam()
    {
        try {
            $TeamData = Team::where('active', 1)->get();
            $Team = TeamResource::collection($TeamData);
            return $Team->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function indexPublicIncoterm()
    {
        try {
            $IncotermData = Incoterm::where('active', 1)->get();
            $Incoterm = IncotermResource::collection($IncotermData);
            return $Incoterm->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicFaq()
    {
        try {
            $FaqData = Faq::get();
            $Faq = FaqResource::collection($FaqData);
            return $Faq->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function indexPublicBenefit()
    {
        try {
            $BenefitData = Benefit::get();
            $Benefit = BenefitResource::collection($BenefitData);
            return $Benefit->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublicContent()
    {
        try {
            $ContentData = Content::where('active', 1)->get();
            $Content = ContentResource::collection($ContentData);
            return $Content->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}

