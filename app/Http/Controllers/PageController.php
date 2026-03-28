<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Http\Resources\Resource;
use App\Models\Page;
use App\Models\PageType;
use App\Models\PricingPlan;
use App\Models\Section;
use App\Models\SectionType;
use App\Services\SectionResourceLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PageController extends Controller
{
    protected $sectionLoader;
    
    public function __construct(SectionResourceLoader $sectionLoader)
    {
        $this->sectionLoader = $sectionLoader;
    }
    
    public function dataTable()
    {
        $pages = QueryBuilder::for(
            Page::with([
                'type', 'media', 'metaSeo', 'parent', 'children',
                'sections' => function($query) {
                    $query->orderBy('order');
                }
            ])->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('published'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($pages);
    }
    
    public function index()
    {
        return Inertia::render('Admin/Pages/Index', []);
    }
    
    public function store(PageRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        
        Page::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'],
            'page_type_id' => $validated['page_type_id'],
            'published' => $validated['published'] ?? false,
            'banner_style' => $validated['banner_style'],
//            'banner_background_type' => $validated['banner_background_type'],
            'banner_background_color' => $validated['banner_background_color'],
        ]);
        
        return back(303)->with('success', 'Page created.');
    }
    
    public function manageSections(Page $page)
    {
        $sectionTypes = SectionType::where('active', '=', true)->get();
        
        return Inertia::render('Admin/Section/ManagePageSections', [
            'page' => $page,
            'sectionTypes' => $sectionTypes,
        ]);
    }
    
    public function update(PageRequest $request, Page $page)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['slug'])->slug('-');
        
        $page->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'],
            'page_type_id' => $validated['page_type_id'],
            'description' => $validated['description'],
            'published' => $validated['published'],
            'banner_style' => $validated['banner_style'],
//            'banner_background_type' => $validated['banner_background_type'],
            'banner_background_color' => $validated['banner_background_color'],
        ]);
        
        return back(303)->with('success', 'Page updated successfully.');
    }
    
    public function destroy(Page $page)
    {
        $page->sections->each(function($section){
            $section->delete();
        });
        
        $page->clearMediaCollection('page-image');
        
        $page->forceDelete();
        
        return back(303)->with('success', 'Page deleted successfully');
    }
    
    public function show($page)
    {
        $page = Page::with('type', 'parent', 'children')->where('slug', '=', $page)->firstOrFail();
        
        $sections = Section::with(
            'section_type',
            'cta_buttons.page',
            'section_cards',
            'hero_slides.media',
            'hero_slides.page',
            'pricing_plans.features',
            'media',
            'section_link.linkable'
        )
            ->where('page_id', $page->id)
            ->orderBy('order')
            ->get();
        
        $sectionResources = $this->sectionLoader->getResourcesForSections($sections);
        
        $customisation = \App\Models\Customisation::orderBy('id')->first();
        
        $seo = $page->metaSeo;
        if ($seo) {
            \App\Models\MetaSeo::applyMeta($seo);
        }
        
        switch ($page->type->name) {
            case 'CMS':
                return view('website.pages.show', compact('page', 'sections', 'customisation', 'sectionResources'));
            
            case 'Landing Page':
                return view('website.pages.landing-page', compact('page', 'sections', 'customisation', 'sectionResources'));
            
            case 'ecommerce_shop':
                return app(\App\Http\Controllers\Ecommerce\EcmShopController::class)->shop($page);
            
            case 'erestaurant_page':
                return app(\App\Http\Controllers\Restaurant\RstRestaurantController::class)->restaurant($page);
            
            case 'Eproperty':
                return app(\App\Http\Controllers\Eproperty\PropertyController::class)->allProperties($page);
            
            default:
                abort(404);
        }
    }
    
    public function storeSeo(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'keywords' => ['required'],
        ]);
        
        if ($page->metaSeo) {
            $page->metaSeo->update($validated);
        } else {
            $page->metaSeo()->create($validated);
        }
        
        return back(303)->with('message', 'Page SEO captured');
    }
    
    public function uploadPageImage(Request $request, $page)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $page = Page::findOrFail($page);
        
        if($request->hasFile('file')) {
            $page->clearMediaCollection('page-image');
            $page->addMedia($validated['file'])
                ->toMediaCollection('page-image');
        }
        
        $files = $page->getMedia('page-image')->first();
        $files->getUrl();
        
        return back(303);
    }
    
    public function deletePageImage(Request $request, $heroSlide)
    {
        $slide = Page::findOrFail($heroSlide);
        $slide->clearMediaCollection('page-image');
        
        return back(303)->with('success', 'Page image deleted successfully.');
    }
}
