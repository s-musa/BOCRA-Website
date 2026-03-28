<?php

namespace App\Http\Controllers;

use App\Services\SectionResourceLoader;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    protected $sectionLoader;
    
    public function __construct(SectionResourceLoader $sectionLoader)
    {
        $this->sectionLoader = $sectionLoader;
    }
    
    public function index()
    {
        $homePage = \App\Models\Page::where('title', '=', 'home')->firstOrFail();
        $customisation = \App\Models\Customisation::orderBy('id')->first() ?? null;
        
        $seo = $homePage->metaSeo;
        
        if($seo) {
            \App\Models\MetaSeo::applyMeta($seo);
        }
        
        if ($homePage) {
            $homePage->load('sections.cta_buttons.page');
            $sections = \App\Models\Section::with('cta_buttons.page', 'section_cards', 'media')
                ->where('page_id', '=', $homePage->id)->orderBy('order')->get() ?? null;
            
            $sectionResources = $this->sectionLoader->getResourcesForSections($sections);
            
            return view('website.home', [
                'page' => $homePage,
                'sections' => $sections,
                'customisation' => $customisation,
                'sectionResources' => $sectionResources,
            ]);
        }
        return view('website.home');
    }
}
