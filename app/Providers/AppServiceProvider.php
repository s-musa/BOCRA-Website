<?php

namespace App\Providers;

use App\Http\Resources\Eproperty\PropertyResource;
use App\Http\Resources\Restaurant\RstRestaurantResource;
use App\Http\Resources\Restaurant\RstRoomResource;
use App\Models\Blog;
use App\Models\BoardMember;
use App\Models\CompanyClient;
use App\Models\Ecommerce\EcmCategory;
use App\Models\Ecommerce\EcmProduct;
use App\Models\Eproperty\ListingType;
use App\Models\Eproperty\PricingModel;
use App\Models\Eproperty\Property;
use App\Models\Eproperty\PropertyCategory;
use App\Models\Eproperty\PropertyType;
use App\Models\Event;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\IconBox;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\Project;
use App\Models\Restaurant\RstRestaurant;
use App\Models\Restaurant\RstRoom;
use App\Models\SectionCard;
use App\Models\SectionCtaButton;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Observers\SectionResourceObserver;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');
        
        $this->loadCompanyData();
        $this->loadCustomisation();
        $this->loadPageReferences();
        $this->loadMenus();
        $this->loadSettings();
        
        $loader = AliasLoader::getInstance();
        $loader->alias('SEOTools', \Artesaos\SEOTools\Facades\SEOTools::class);
        
        Service::observe(SectionResourceObserver::class);
        Project::observe(SectionResourceObserver::class);
        Blog::observe(SectionResourceObserver::class);
        PricingPlan::observe(SectionResourceObserver::class);
        Product::observe(SectionResourceObserver::class);
        Event::observe(SectionResourceObserver::class);
        Faq::observe(SectionResourceObserver::class);
        Skill::observe(SectionResourceObserver::class);
        BoardMember::observe(SectionResourceObserver::class);
        Partner::observe(SectionResourceObserver::class);
        CompanyClient::observe(SectionResourceObserver::class);
        HeroSlide::observe(SectionResourceObserver::class);
        SectionCtaButton::observe(SectionResourceObserver::class);
        SectionCard::observe(SectionResourceObserver::class);
        IconBox::observe(SectionResourceObserver::class);
        
        Vite::prefetch(concurrency: 3);
    }
    
    protected function loadCompanyData(): void
    {
        if (Schema::hasTable('companies')) {
            $company = \App\Models\Company::orderBy('id')->first();
            
            if ($company) {
                $logo = $company->hasMedia('logo') ? $company->getMedia('logo')->sortByDesc('created_at')->first()->getUrl() : null;
                $footerLogo = $company->hasMedia('footer-logo') ? $company->getMedia('footer-logo')->sortByDesc('created_at')->first()->getUrl() : null;
                $favicon = $company->hasMedia('favicon') ? $company->getMedia('favicon')->sortByDesc('created_at')->first()->getUrl() : null;
                
                View::share([
                    'company' => $company,
                    'logo' => $logo,
                    'footerLogo' => $footerLogo,
                    'favicon' => $favicon,
                ]);
            }
        }
    }
    
    protected function loadCustomisation(): void
    {
        if (Schema::hasTable('customisations')) {
            $customisation = \App\Models\Customisation::orderBy('id')->first();
            if ($customisation) {
                View::share(['customisation' => $customisation]);
            }
        }
    }
    
    protected function loadPageReferences(): void
    {
        if (Schema::hasTable('pages')) {
            View::share([
                'homePage' => Page::where('title', 'Home')->first(),
                'aboutPage' => Page::where('title', 'About')->first(),
                'contactPage' => Page::where('title', 'Contact')->first(),
                'projectsPage' => Page::where('title', 'Projects')->first(),
            ]);
        }
    }
    
    protected function loadMenus(): void
    {
        if (Schema::hasTable('menus')) {
            $menus = \App\Models\Menu::with([
                        'children',
                        'page' => function($query) {
                            $query->with(['parent', 'children'])->whereNull('parent_id');
                        }
                    ])
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->orderByDesc('created_at')
                    ->get();
            
            $footerMenus = \App\Models\Menu::with('page', 'children')
                ->where('type', '=', 'page')
                ->orderBy('order')
                ->orderByDesc('created_at')
                ->get();
            
            View::share([
                'menus' => $menus,
                'footerMenus' => $footerMenus,
            ]);
        }
    }
    
    protected function loadSettings(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = \App\Models\Setting::with('module')->get();
            
            View::share([
                'settings' => $settings,
            ]);
        }
    }
}
