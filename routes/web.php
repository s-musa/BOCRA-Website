<?php

use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/wp-admin', function () {
        return redirect()->route('admin.dashboard');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    /**
     * DATA ROUTES
     */
    Route::group([
        'prefix' => 'data',
        'as' => 'data.'
    ], function () {
        Route::get('/tags', [\App\Http\Controllers\DataController::class, 'tags'])->name('tags');
    });
    
    /**
     * DATATABLE ROUTES
     */
    Route::group([
        'prefix' => 'datatable',
        'as' => 'datatable.'
    ], function () {
        Route::get('/customisations', [\App\Http\Controllers\CustomisationController::class, 'dataTable'])->name('customisations');
        Route::get('/services', [\App\Http\Controllers\ServiceController::class, 'dataTable'])->name('services');
        Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'dataTable'])->name('projects');
        Route::get('/products', [\App\Http\Controllers\ProductController::class, 'dataTable'])->name('products');
        Route::get('/faqs', [\App\Http\Controllers\FaqController::class, 'dataTable'])->name('faqs');
        Route::get('/skills', [\App\Http\Controllers\SkillController::class, 'dataTable'])->name('skills');
        Route::get('/board-members', [\App\Http\Controllers\BoardMemberController::class, 'dataTable'])->name('board-members');
        Route::get('/blogs', [\App\Http\Controllers\BlogController::class, 'dataTable'])->name('blogs');
        Route::get('/events', [\App\Http\Controllers\EventController::class, 'dataTable'])->name('events');
        Route::get('/partners', [\App\Http\Controllers\PartnerController::class, 'dataTable'])->name('partners');
        Route::get('/clients', [\App\Http\Controllers\CompanyClientController::class, 'dataTable'])->name('clients');
        Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'dataTable'])->name('categories');
        Route::get('/product-types', [\App\Http\Controllers\ProductTypeController::class, 'dataTable'])->name('product-types');
        
        Route::get('/section-types', [\App\Http\Controllers\SectionTypeController::class, 'dataTable'])->name('section-types');
        Route::get('/page-types', [\App\Http\Controllers\PageTypeController::class, 'dataTable'])->name('page-types');
        Route::get('/tags', [\App\Http\Controllers\TagController::class, 'dataTable'])->name('tags');
        
        Route::get('/menus', [\App\Http\Controllers\MenuController::class, 'dataTable'])->name('menus');
        Route::get('/pages', [\App\Http\Controllers\PageController::class, 'dataTable'])->name('pages');
        
        Route::get('/page-sections', [\App\Http\Controllers\SectionController::class, 'dataTable'])->name('sections');
        Route::get('/section-cards', [\App\Http\Controllers\SectionCardController::class, 'dataTable'])->name('section-cards');
        Route::get('/icon-boxes', [\App\Http\Controllers\IconBoxController::class, 'dataTable'])->name('icon-boxes');
        Route::get('/cta-buttons', [\App\Http\Controllers\SectionCtaButtonController::class, 'dataTable'])->name('cta-buttons');
        Route::get('/section-faqs', [\App\Http\Controllers\SectionFaqController::class, 'dataTable'])->name('section-faqs');
        Route::get('/section-testimonials', [\App\Http\Controllers\SectionTestimonialController::class, 'dataTable'])->name('section-testimonials');
        Route::get('/pricing-plans', [\App\Http\Controllers\PricingPlanController::class, 'dataTable'])->name('plans');
        Route::get('/pricing-plan-features', [\App\Http\Controllers\PricingPlanFeatureController::class, 'dataTable'])->name('features');
        
        Route::get('/service-features', [\App\Http\Controllers\ServiceFeatureController::class, 'dataTable'])->name('service-features');
        Route::get('/sub-services', [\App\Http\Controllers\SubServiceController::class, 'dataTable'])->name('sub-services');
        Route::get('/sub-service-features', [\App\Http\Controllers\ServiceFeatureController::class, 'dataTable'])->name('sub-service-features');
        
        Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'dataTable']);
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'dataTable'])->name('users');
        Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'dataTable'])->name('roles');
        Route::get('/permissions', [\App\Http\Controllers\PermissionController::class, 'dataTable'])->name('permissions');
        Route::get('/meta-seos', [\App\Http\Controllers\MetaSeoController::class, 'dataTable'])->name('metas');
        
        Route::get('/currencies', [\App\Http\Controllers\CurrencyController::class, 'dataTable'])->name('currencies');
        Route::get('/countries', [\App\Http\Controllers\CurrencyController::class, 'countries'])->name('countries');
        
        Route::get('/modules', [\App\Http\Controllers\ModuleController::class, 'dataTable'])->name('modules');
    });
    
    /**
     *  ADMIN ROUTES
     **/
    Route::group([
        'prefix' => 'wp-admin',
        'as' => 'admin.',
    ], function () {
        
        Route::group([
            'prefix' => 'components',
        ], function () {
            Route::get('/', [\App\Http\Controllers\ComponentController::class, 'index'])->name('components.index');
            Route::resource('/services', \App\Http\Controllers\ServiceController::class)
                ->names('components.services')->only('store', 'update', 'destroy');
            Route::resource('/projects', \App\Http\Controllers\ProjectController::class)
                ->names('components.projects')->only('store', 'update', 'destroy');
            Route::resource('/products', \App\Http\Controllers\ProductController::class)
                ->names('components.products')->only('store', 'update', 'destroy');
            Route::resource('/board-members', \App\Http\Controllers\BoardMemberController::class)
                ->names('components.board-members')->only('store', 'update', 'destroy');
            Route::resource('/partners', \App\Http\Controllers\PartnerController::class)
                ->names('components.partners')->only('store', 'update', 'destroy');
            Route::resource('/company-clients', \App\Http\Controllers\CompanyClientController::class)
                ->names('components.clients')->only('store', 'update', 'destroy');
            Route::resource('/faqs', \App\Http\Controllers\FaqController::class)
                ->names('components.faqs')->only('store', 'update', 'destroy');
            Route::resource('/skills', \App\Http\Controllers\SkillController::class)
                ->names('components.skills')->only('store', 'update', 'destroy');
            Route::resource('/blogs', \App\Http\Controllers\BlogController::class)
                ->names('components.blogs')->only('store', 'update', 'destroy');
            Route::resource('/events', \App\Http\Controllers\EventController::class)
                ->names('components.events')->only('store', 'update', 'destroy');
        });
        
        Route::group([
            'prefix' => 'configurations',
            'as' => 'settings.'
        ], function () {
            Route::get('/', [\App\Http\Controllers\ConfigurationController::class, 'index'])->name('index');
            Route::resource('/categories', \App\Http\Controllers\CategoryController::class)
                ->names('categories')->only('store', 'update', 'destroy');
            Route::resource('/pricing-plan-features', \App\Http\Controllers\PricingPlanFeatureController::class)
                ->names('features')->only('store', 'update', 'destroy');
            Route::resource('/tags', \App\Http\Controllers\TagController::class)
                ->names('tags')->only('store', 'update', 'destroy');
        });
        
        Route::group([
            'prefix' => 'medias',
            'as' => 'medias.'
        ], function () {
//            Route::delete('/{media}', [MediaController::class, 'destroy'])->name('medias.destroy');
            
            Route::post('/company', [\App\Http\Controllers\CompanyController::class, 'uploadMedia'])->name('company.media');
            Route::delete('/{company}/logo', [\App\Http\Controllers\MediaController::class, 'deleteLogo'])->name('delete.logo');
            Route::delete('/{company}/footer-logo', [\App\Http\Controllers\MediaController::class, 'deleteFooterLogo'])->name('delete.footer-logo');
            Route::delete('/{company}/favicon', [\App\Http\Controllers\MediaController::class, 'deleteFavicon'])->name('delete.favicon');
            
            Route::post('/banner-image/{customisation}', [\App\Http\Controllers\CustomisationController::class, 'uploadBannerImage'])->name('upload.banner-image');
            Route::delete('/delete-banner-image/{customisation}', [\App\Http\Controllers\CustomisationController::class, 'deleteBannerImage'])->name('delete.banner-image');
            
            Route::post('/services/{service}', [\App\Http\Controllers\ServiceController::class, 'uploadServiceImage'])->name('upload.service-image');
            Route::delete('/services/{service}/delete', [\App\Http\Controllers\ServiceController::class, 'deleteServiceImage'])->name('delete.service-image');
            Route::post('/services/{service}/gallery', [\App\Http\Controllers\ServiceController::class, 'uploadServiceGallery'])->name('upload.service-gallery');
            Route::delete('/services/{service}/{media}', [\App\Http\Controllers\ServiceController::class, 'deleteMedia'])
                ->name('service.media.destroy');
            
            Route::post('/sub-services/{sub-service}', [\App\Http\Controllers\SubServiceController::class, 'uploadSubServiceImage'])->name('upload.sub-service-image');
            Route::delete('/sub-services/{sub-service}/delete', [\App\Http\Controllers\SubServiceController::class, 'deleteSubServiceImage'])->name('delete.sub-service-image');
            
            Route::post('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'uploadProjectImage'])->name('upload.project-image');
            Route::delete('/projects/{project}/delete', [\App\Http\Controllers\ProjectController::class, 'deleteProjectImage'])->name('delete.project-image');
            Route::post('/projects/{project}/gallery', [\App\Http\Controllers\ProjectController::class, 'uploadProjectGallery'])->name('upload.project-gallery');
            
            Route::post('/blogs/{blog}', [\App\Http\Controllers\BlogController::class, 'uploadBlogImage'])->name('upload.blog-image');
            Route::post('/blogs/{blog}/delete', [\App\Http\Controllers\BlogController::class, 'deleteBlogImage'])->name('delete.blog-image');
            Route::post('/blogs/{blog}/gallery', [\App\Http\Controllers\BlogController::class, 'uploadBlogGallery'])->name('upload.blog-gallery');
            
            Route::post('/events/{event}', [\App\Http\Controllers\EventController::class, 'uploadEventImage'])->name('upload.event-image');
            Route::post('/events/{event}/delete', [\App\Http\Controllers\EventController::class, 'deleteEventImage'])->name('delete.event-image');
            Route::post('/events/{event}/gallery', [\App\Http\Controllers\EventController::class, 'uploadEventGallery'])->name('upload.event-gallery');
            
            Route::post('/gallery/upload', [\App\Http\Controllers\GalleryController::class, 'upload'])->name('gallery.upload');
            Route::post('{media}/gallery/delete', [\App\Http\Controllers\GalleryController::class, 'upload'])->name('gallery.delete');
            
            Route::post('/products', [MediaController::class, 'uploadProductImage'])->name('upload.product-image');
            Route::post('/board-members', [MediaController::class, 'uploadBoardMemberImage'])->name('upload.board-member-image');
            Route::post('/partners', [MediaController::class, 'uploadPartnerImage'])->name('upload.partner-image');
            Route::post('/company-clients/{id}', [\App\Http\Controllers\CompanyClientController::class, 'uploadImage'])->name('upload.company-client-image');
            
            Route::post('/sections/{section}', [\App\Http\Controllers\SectionController::class, 'uploadSectionImage'])->name('upload.section-image');
            Route::delete('/sections/{section}/delete', [\App\Http\Controllers\SectionController::class, 'deleteSectionImage'])->name('delete.section-image');
            Route::post('/sections/{section}/gallery', [\App\Http\Controllers\SectionController::class, 'uploadGallery'])->name('upload.section-gallery');
            Route::delete('{media}/sections/{section}/gallery', [\App\Http\Controllers\SectionController::class, 'deleteGalleryMedia'])->name('delete.section-gallery');
            
            Route::post('/hero-slides/{hero_slide}', [\App\Http\Controllers\HeroSlideController::class, 'uploadSlideImage'])->name('upload.hero-slide-image');
            Route::delete('/hero-slides/{hero_slide}/delete', [\App\Http\Controllers\HeroSlideController::class, 'deleteSlideImage'])->name('delete.hero-slide-image');
            
            Route::post('/section-cards/{section_card}/card-image', [\App\Http\Controllers\SectionCardController::class, 'uploadSectionCardImage'])->name('upload.card-image');
            
            Route::post('/pages/{page}/page-image', [\App\Http\Controllers\PageController::class, 'uploadPageImage'])->name('upload.page-image');
            Route::delete('/pages/{page}/delete', [\App\Http\Controllers\PageController::class, 'deletePageImage'])->name('delete.page-image');
        });
        
        Route::group([
            'prefix' => 'pages',
            'as' => 'pages.'
        ], function () {
            Route::get('/{page}/sections', [\App\Http\Controllers\SectionController::class, 'index'])->name('sections.index');
            Route::get('/{page}/sections/create', [\App\Http\Controllers\SectionController::class, 'create'])->name('sections.create');
            Route::get('/{page}/sections/{section}/edit', [\App\Http\Controllers\SectionController::class, 'edit'])->name('sections.edit');
            Route::get('/{page}/manage-sections', [\App\Http\Controllers\PageController::class, 'manageSections'])->name('manage-sections');
            Route::post('/sections', [\App\Http\Controllers\SectionController::class, 'store'])->name('sections.store');
            Route::patch('/{page}/sections/', [\App\Http\Controllers\SectionController::class, 'update'])->name('sections.update');
            Route::delete('/sections/{section}', [\App\Http\Controllers\SectionController::class, 'destroy'])->name('sections.delete');
            Route::post('/medias/section', [MediaController::class, 'uploadSectionMedia'])->name('sections.media');
            Route::post('/medias/section/background', [MediaController::class, 'uploadSectionBackgroundMedia'])->name('sections.background-media');
            Route::delete('/medias/{section}', [MediaController::class, 'deleteSectionMedia'])->name('sections.delete-media');
//        Route::delete('/medias/{section}/background', [MediaController::class, 'deleteSectionBackgroundMedia'])->name('sections.delete-background');
            
            Route::post('/sections/move-section', [\App\Http\Controllers\SectionController::class, 'moveSection'])->name('sections.move-section');
            
            Route::post('/{page}/meta-seo', [\App\Http\Controllers\PageController::class, 'storeSeo'])->name('meta-seo.store');
        });
        
        Route::post('/services/{service}/meta-seo', [\App\Http\Controllers\ServiceController::class, 'storeSeo'])->name('services.meta-seo');
        Route::post('/projects/{project}/meta-seo', [\App\Http\Controllers\ProjectController::class, 'storeSeo'])->name('projects.meta-seo');
        
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics/refresh', [\App\Http\Controllers\DashboardController::class, 'refreshAnalytics'])
            ->name('analytics.refresh');
        Route::post('/users/{user}/permissions', [\App\Http\Controllers\UserController::class, 'updatePermission']);
        
        Route::get('/customisations', [\App\Http\Controllers\CustomisationController::class, 'index'])->name('customisations.index');
        Route::post('/customisations', [\App\Http\Controllers\CustomisationController::class, 'store'])->name('customisations.store');
        Route::patch('/customisations/{customisation}', [\App\Http\Controllers\CustomisationController::class, 'update'])->name('customisations.update');
        Route::patch('/customisations/{customisation}/general-styles', [\App\Http\Controllers\CustomisationController::class, 'generalCustomisation'])->name('customisations.general-styles');
        Route::post('/customisations/{customisation}/defaults', [\App\Http\Controllers\CustomisationController::class, 'defaults'])->name('customisations.defaults');
        Route::patch('/customisations/{customisation}/button-styles', [\App\Http\Controllers\CustomisationController::class, 'setButtonStyles'])->name('customisations.button-styles');
        Route::patch('/customisations/{customisation}/header-styles', [\App\Http\Controllers\CustomisationController::class, 'updateHeaderStyles'])->name('customisations.header-styles');
        Route::patch('/customisations/{customisation}/footer-styles', [\App\Http\Controllers\CustomisationController::class, 'updateFooterStyles'])->name('customisations.footer-styles');
        Route::patch('/customisations/{customisation}/banner-styles', [\App\Http\Controllers\CustomisationController::class, 'updateBannerStyles'])->name('customisations.banner-styles');
        
        Route::post('/sections/update-section-order', [\App\Http\Controllers\SectionController::class, 'updateOrder'])
            ->name('pages.update-section-order');
        Route::post('/menus/update-menu-order', [\App\Http\Controllers\MenuController::class, 'updateOrder'])
            ->name('pages.update-menu-order');
        Route::post('/board-members/update-members-order', [\App\Http\Controllers\BoardMemberController::class, 'updateOrder'])
            ->name('pages.update-member-order');
        
        /** SITEMAP ROUTE */
        
        
        /** MODULE ROUTES */
        Route::get('/modules', [\App\Http\Controllers\ModuleController::class, 'index'])->name('modules.index');
        Route::post('/modules', [\App\Http\Controllers\ModuleController::class, 'install'])->name('modules.install');
        Route::post('/modules/enable', [\App\Http\Controllers\ModuleController::class, 'enable'])->name('modules.enable');
        
        
        /** RESOURCE ROUTES */
        Route::resource('/menus', \App\Http\Controllers\MenuController::class)->names('menus')->except('show', 'create', 'edit');
        Route::resource('/page-types', \App\Http\Controllers\PageTypeController::class)->names('page-types')->only('store', 'update', 'destroy');
        Route::resource('/pages', \App\Http\Controllers\PageController::class)->names('pages')->except('create', 'edit');
        Route::resource('/sections-types', \App\Http\Controllers\SectionTypeController::class)->names('section-types')->only('store', 'update', 'destroy');
        Route::resource('/sections-cta-buttons', \App\Http\Controllers\SectionCtaButtonController::class)->names('cta-buttons')->only('store', 'update', 'destroy');
        Route::resource('/sections-cards', \App\Http\Controllers\SectionCardController::class)->names('section-cards')->only('store', 'update', 'destroy');
        Route::resource('/hero-slides', \App\Http\Controllers\HeroSlideController::class)->names('hero-slides')->only('store', 'update', 'destroy');
        Route::resource('/icon-boxes', \App\Http\Controllers\IconBoxController::class)->names('icon-boxes')->only('store', 'update', 'destroy');
        Route::resource('/section-faqs', \App\Http\Controllers\SectionFaqController::class)->names('section-faqs')->only('store', 'update', 'destroy');
        Route::resource('/section-testimonials', \App\Http\Controllers\SectionTestimonialController::class)->names('section-testimonials')->only('store', 'update', 'destroy');
        Route::resource('/pricing-plans', \App\Http\Controllers\PricingPlanController::class)->names('pricing-plans')->only('store', 'update', 'destroy');
        
        Route::resource('/service-features', \App\Http\Controllers\ServiceFeatureController::class)->names('service-features')->only('store', 'update', 'destroy');
        Route::resource('/sub-services', \App\Http\Controllers\SubServiceController::class)->names('sub-services')->only('store', 'update', 'destroy');
        Route::resource('/sub-service-features', \App\Http\Controllers\SubServiceFeatureController::class)->names('sub-service-features')->only('store', 'update', 'destroy');
        
        Route::resource('/companies', \App\Http\Controllers\CompanyController::class)->names('companies')->except('edit', 'show', 'destroy');
        Route::resource('/users', \App\Http\Controllers\UserController::class)->names('users');
        Route::resource('/roles', \App\Http\Controllers\RoleController::class)->names('roles');
        Route::resource('/permissions', \App\Http\Controllers\PermissionController::class)->names('permissions');
        Route::resource('/meta-seos', \App\Http\Controllers\MetaSeoController::class)->names('meta-seos')->except('create', 'edit', 'show');
    });
});

require __DIR__.'/auth.php';

// TODO; add a middleware for each module to handle redirects when not installed
require __DIR__.'/customer.php';


Route::post('/send-message', [ContactFormController::class, 'send'])->middleware('throttle:3,60')->name('send-message');

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::get('/services/{project}', [\App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');
Route::get('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
Route::get('/blogs/{blog}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');
Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');

Route::get('/page-sitemap', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

