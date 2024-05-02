<?php
namespace App\Providers;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\BenefitRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\ContactPersonRepositoryInterface;
use App\Interfaces\ContactUsRepositoryInterface;
use App\Interfaces\ContentRepositoryInterface;
use App\Interfaces\ContinentRepositoryInterface;
use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\CouponRepositoryInterface;
use App\Interfaces\DelegateRepositoryInterface;
use App\Interfaces\DietaryRepositoryInterface;
use App\Interfaces\EmailTemplateRepositoryInterface;
use App\Interfaces\EventContactUsRepositoryInterface;
use App\Interfaces\EventDayRepositoryInterface;
use App\Interfaces\EventHelpCenterRepositoryInterface;
use App\Interfaces\EventItemRepositoryInterface;
use App\Interfaces\EventMenuRepositoryInterface;
use App\Interfaces\EventPageRepositoryInterface;
use App\Interfaces\EventRepositoryInterface;
use App\Interfaces\EventSectionPageRepositoryInterface;
use App\Interfaces\FaqRepositoryInterface;
use App\Interfaces\FollowingCaseRepositoryInterface;
use App\Interfaces\GroupRepositoryInterface;
use App\Interfaces\IncotermRepositoryInterface;
use App\Interfaces\MenuRepositoryInterface;
use App\Interfaces\NetworkRepositoryInterface;
use App\Interfaces\NewsRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use App\Interfaces\PageRepositoryInterface;
use App\Interfaces\PageSectionRepositoryInterface;
use App\Interfaces\PartnerRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\ProgramRepositoryInterface;
use App\Interfaces\ReferralRepositoryInterface;
use App\Interfaces\RefRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\RoomRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\SettingEventRepositoryInterface;
use App\Interfaces\SettingRepositoryInterface;
use App\Interfaces\SliderRepositoryInterface;
use App\Interfaces\SponsorRepositoryInterface;
use App\Interfaces\SponsorshipItemRepositoryInterface;
use App\Interfaces\SpouseRepositoryInterface;
use App\Interfaces\SubMenuRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use App\Interfaces\TestimonialRepositoryInterface;
use App\Interfaces\TimeSlotRepositoryInterface;
use App\Interfaces\TradeReferenceRepositoryInterface;
use App\Interfaces\TshirtSizeRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VisitRepositoryInterface;
use App\Repositories\AdminRepository;
use App\Repositories\BenefitRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\CityRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ConferenceRepository;
use App\Repositories\ContactPersonRepository;
use App\Repositories\ContactUsRepository;
use App\Repositories\ContentRepository;
use App\Repositories\ContinentRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CouponRepository;
use App\Repositories\DelegateRepository;
use App\Repositories\DietaryRepository;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\EventContactUsRepository;
use App\Repositories\EventDayRepository;
use App\Repositories\EventHelpCenterRepository;
use App\Repositories\EventItemRepository;
use App\Repositories\EventMenuRepository;
use App\Repositories\EventPageRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventSectionPageRepository;
use App\Repositories\FaqRepository;
use App\Repositories\FollowingCaseRepository;
use App\Repositories\GroupRepository;
use App\Repositories\IncotermRepository;
use App\Repositories\MenuRepository;
use App\Repositories\NetworkRepository;
use App\Repositories\NewsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PageRepository;
use App\Repositories\PageSectionRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\ReferralRepository;
use App\Repositories\RefRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SettingEventRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SliderRepository;
use App\Repositories\SponsorRepository;
use App\Repositories\SponsorshipItemRepository;
use App\Repositories\SpouseRepository;
use App\Repositories\SubMenuRepository;
use App\Repositories\TeamRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\TimeSlotRepository;
use App\Repositories\TradeReferenceRepository;
use App\Repositories\TshirtSizeRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(SettingEventRepositoryInterface::class, SettingEventRepository::class);
        $this->app->bind(EventPageRepositoryInterface::class, EventPageRepository::class);
        $this->app->bind(EventMenuRepositoryInterface::class, EventMenuRepository::class);
        $this->app->bind(EventSectionPageRepositoryInterface::class, EventSectionPageRepository::class);
        $this->app->bind(EventItemRepositoryInterface::class, EventItemRepository::class);
        $this->app->bind(ConferenceRepositoryInterface::class, ConferenceRepository::class);
        $this->app->bind(TshirtSizeRepositoryInterface::class, TshirtSizeRepository::class);
        $this->app->bind(SponsorRepositoryInterface::class, SponsorRepository::class);
        $this->app->bind(DietaryRepositoryInterface::class, DietaryRepository::class);
        $this->app->bind(EventContactUsRepositoryInterface::class, EventContactUsRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(DelegateRepositoryInterface::class, DelegateRepository::class);
        $this->app->bind(SponsorshipItemRepositoryInterface::class, SponsorshipItemRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(SpouseRepositoryInterface::class, SpouseRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(EventHelpCenterRepositoryInterface::class, EventHelpCenterRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(EventDayRepositoryInterface::class, EventDayRepository::class);
        $this->app->bind(TimeSlotRepositoryInterface::class, TimeSlotRepository::class);
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);

       ////////////////////////////////////////////////////////////////////////////////////////////////

        $this->app->bind(CertificateRepositoryInterface::class, CertificateRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->bind(BenefitRepositoryInterface::class, BenefitRepository::class);
        $this->app->bind(RefRepositoryInterface::class, RefRepository::class);
        $this->app->bind(ContactUsRepositoryInterface::class, ContactUsRepository::class);
        $this->app->bind(ContinentRepositoryInterface::class, ContinentRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(EmailTemplateRepositoryInterface::class, EmailTemplateRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(IncotermRepositoryInterface::class, IncotermRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(SubMenuRepositoryInterface::class, SubMenuRepository::class);
        $this->app->bind(NetworkRepositoryInterface::class, NetworkRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(PageSectionRepositoryInterface::class, PageSectionRepository::class);
        $this->app->bind(PartnerRepositoryInterface::class, PartnerRepository::class);
        $this->app->bind(ReferralRepositoryInterface::class, ReferralRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);
        $this->app->bind(ContactPersonRepositoryInterface::class, ContactPersonRepository::class);
        $this->app->bind(FollowingCaseRepositoryInterface::class, FollowingCaseRepository::class);
        $this->app->bind(TradeReferenceRepositoryInterface::class, TradeReferenceRepository::class);
    }
}




