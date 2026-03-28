<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Http\Resources\Resource;
use App\Models\HeroSlide;
use App\Models\IconBox;
use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Section;
use App\Models\SectionCard;
use App\Models\SectionCtaButton;
use App\Models\SectionLink;
use App\Models\SectionType;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Mockery\Exception;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SectionController extends Controller
{
    public function datatable()
    {
        $sections = QueryBuilder::for(
            Section::with([
                'page', 'cta_buttons.page',
                'icon_boxes', 'faqs', 'media',
                'testimonials', 'pricing_plans.features',
                'section_type', 'section_link.linkable',
                'section_cards' => function ($query) {
                    $query->with('page', 'media');
                },
                'hero_slides' => function ($query) {
                    $query->with(['page', 'media']);
                },
            ])
            ->orderBy('order')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('page_id'),
        ])->jsonPaginate();
        
        return Resource::collection($sections);
    }
    
    public function index(Page $page)
    {
        $sectionTypes = SectionType::where('active', '=', true)->get();
        
        return Inertia::render('Admin/Section/Index', [
            'page' => $page,
            'sectionTypes' => $sectionTypes,
        ]);
    }
    
    public function create(Page $page)
    {
        $sectionTypes = SectionType::where('active', '=', true)->get();
        
        return Inertia::render('Admin/Section/Create', [
            'page' => $page,
            'sectionTypes' => $sectionTypes,
        ]);
    }
    
    public function store(SectionRequest $request)
    {
        $validated = $request->validated();
        $purifier = new HtmlPurifierService();
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        $page = Page::find($validated['page_id']);
        $page->load('type');
        
        $spaSectionId = '';
        
        if($validated['spa_section_name']) {
            $spaSectionId = Str::of($validated['spa_section_name'])->slug('-');
        }
        
        DB::beginTransaction();
        
        try {
            $type = SectionType::where('value', '=', $validated['type'])->firstOrFail();
            $section = Section::create([
                'page_id' => $validated['page_id'],
                'type' => $validated['type'],
                'section_type_id' => $type->id ?? null,
                'spa_section_name' => $validated['spa_section_name'] ?? null,
                'spa_section_id' => $spaSectionId ?? null,
                'title' => $validated['title'],
                'sub_title' => $validated['sub_title'],
                'component_type' => $validated['component_type'],
                'details' => $validated['details'],
                'include_contact_cards' => $validated['include_contact_cards'],
                'section_has_image' => $validated['section_has_image'],
                'section_image_first' => $validated['section_image_first'],
                'has_cta_buttons' => $validated['has_cta_buttons'],
                'section_has_map' => $validated['section_has_map'],
                'sliding_hero_section' => $validated['sliding_hero_section'],
                'section_has_bg' => $validated['section_has_bg'],
                'section_background_type' => $validated['section_background_type'],
                'section_background_color' => $validated['section_background_color'],
                'section_style' => $validated['section_style'],
                'width_style' => $validated['width_style'],
                'width' => $validated['width'],
                'form_title' => $validated['form_title'],
                'form_sub_title' => $validated['form_sub_title'],
                'form_button_text' => $validated['form_button_text'],
                'video_link' => $validated['video_link'],
                'map_link' => $validated['map_link'],
                'order' => Section::max('order') + 1,
            ]);
            
            if($request->hasFile('media')) {
                $section->clearMediaCollection('section_image');
                $section->addMedia($validated['media'])->toMediaCollection('section_image');
            }
            
            if($validated['section_has_bg'] && $validated['section_background_type'] === 'image-bg' && $request->hasFile('background_image')) {
                $section->clearMediaCollection('section_bg');
                $section->addMedia($validated['background_image'])->toMediaCollection('section_bg');
            }
            
            if ($request->hasFile('gallery_media')) {
                foreach ($request->file('gallery_media') as $image) {
                    $section->addMedia($image)
                        ->toMediaCollection('section-gallery');
                }
            }
            
            if (isset($validated['cta_buttons']) && is_array($validated['cta_buttons'])) {
                $ctaButtons = collect($validated['cta_buttons'])
                    ->filter(function ($ctaButton) {
                        return isset($ctaButton['page']['id']);
                    })
                    ->map(function ($ctaButton) use ($section) {
                        return [
                            'section_id' => $section->id,
                            'page_id' => $ctaButton['page']['id'],
                            'cta_link_type' => $ctaButton['cta_link_type'] ?? null,
                            'custom_url' => $ctaButton['custom_url'] ?? null,
                            'section_url' => $ctaButton['section_url'] ?? null,
                            'cta_button_text' => $ctaButton['cta_button_text'],
                            'cta_button_type' => $ctaButton['cta_button_type']['value'],
                            'created_at' => now()->toDateTimeString(),
                            'updated_at' => now()->toDateTimeString(),
                        ];
                    })->toArray();
                
                if(!empty($ctaButtons)) {
                    SectionCtaButton::insert($ctaButtons);
                }
            }
            
            if (isset($validated['section_cards']) && is_array($validated['section_cards'])) {
                $cards = collect($validated['section_cards'])
                    ->filter(function ($card) {
                        return isset($card['title']);
                    })
                    ->map(function ($card) use ($section, $purifier) {
                        return [
                            'section_id' => $section->id,
                            'title' => $card['title'],
                            'icon' => $card['icon'] ?? null,
                            'details' => $purifier->purify($card['details']),
                            'has_link' => $card['has_link'] ?? false,
                            'card_link_type' => $card['card_link_type'] ?? null,
                            'custom_url' => $card['custom_url'] ?? null,
                            'section_url' => $card['section_url'] ?? null,
                            'page_id' => $card['page_id'] ?? null,
                            'created_at' => now()->toDateTimeString(),
                            'updated_at' => now()->toDateTimeString(),
                        ];
                    })->toArray();
                
                if(!empty($cards)) {
                    SectionCard::insert($cards);
                }
            }
            
            if (isset($validated['hero_slides']) && is_array($validated['hero_slides'])) {
                foreach ($validated['hero_slides'] as $slide) {
//                    if (!isset($slide['title'])) {
//                        continue; // skip invalid slide
//                    }
                    $details = null;
                    if($slide['details']) {
                        $details = $purifier->purify($slide['details']) ?? null;
                    }
                    
                    $heroSlide = HeroSlide::create([
                        'section_id' => $section->id,
                        'title' => $slide['title'],
                        'sub_title' => $slide['sub_title'] ?? null,
                        'details' => $details,
                        'page_id' => $slide['page']['id'] ?? null,
                        'cta_button_text' => $slide['cta_button_text'] ?? null,
                        'cta_button_type' => $slide['cta_button_type']['value'] ?? null,
                    ]);
                    
                    if (!empty($slide['media'])) {
                        $heroSlide->addMedia($slide['media'])
                            ->toMediaCollection('slide_image');
                    }
                }
            }
            
            if (isset($validated['icon_boxes']) && is_array($validated['icon_boxes'])) {
                $iconBoxes = collect($validated['icon_boxes'])
                    ->filter(function ($card) {
                        return isset($card['title']);
                    })
                    ->map(function ($card) use ($section, $purifier) {
                        return [
                            'section_id' => $section->id,
                            'title' => $card['title'],
                            'icon' => $card['icon'] ?? null,
                            'details' => $purifier->purify($card['details']),
                            'has_link' => $card['has_link'] ?? false,
                            'icon_link_type' => $card['icon_link_type'] ?? null,
                            'custom_url' => $card['custom_url'] ?? null,
                            'section_url' => $card['section_url'] ?? null,
                            'page_id' => $card['page_id'] ?? null,
                            'created_at' => now()->toDateTimeString(),
                            'updated_at' => now()->toDateTimeString(),
                        ];
                    })->toArray();
                
                if(!empty($iconBoxes)) {
                    IconBox::insert($iconBoxes);
                }
            }
            
            if($validated['type'] === 'section-ecommerce-product-category' && isset($validated['category_id'])) {
                SectionLink::create([
                    'section_id' => $section->id,
                    'linkable_type' => \App\Models\Ecommerce\EcmCategory::class,
                    'linkable_id'   => $validated['category_id'],
                ]);
            }
            
            DB::commit();
            return back(303);
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Error: ' . $exception->getMessage());
            report($exception);
            return redirect()->back()->withInput()->withErrors(['message' => 'Failed to save page sections. Please try again.']);
        }
    }
    
    public function edit(Page $page)
    {
        $sectionTypes = SectionType::where('active', '=', true)->get();
        
        return Inertia::render('Admin/Section/Edit', [
            'page' => $page,
            'sectionTypes' => $sectionTypes,
        ]);
    }
    
    public function update(SectionRequest $request, $sectionId)
    {
        $section = Section::findOrFail($sectionId);
        $validated = $request->validated();
        
        $purifier = new HtmlPurifierService();
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        if($validated['include_contact_cards']) {
            $validated['section_has_image'] = false;
//            $validated['details'] = null;
        }
        
        $spaSectionId = '';
        
        if($validated['spa_section_name']) {
            $spaSectionId = Str::of($validated['spa_section_name'])->slug('-');
        }
        
        DB::beginTransaction();
        
        try {
            $type = SectionType::where('value', '=', $validated['type'])->firstOrFail();
            $section->update([
                'type' => $validated['type'],
                'section_type_id' => $type->id ?? null,
                'spa_section_name' => $validated['spa_section_name'] ?? null,
                'spa_section_id' => $spaSectionId ?? null,
                'title' => $validated['title'],
                'sub_title' => $validated['sub_title'],
                'component_type' => $validated['component_type'],
                'details' => $validated['details'],
                'include_contact_cards' => $validated['include_contact_cards'],
                'section_has_image' => $validated['section_has_image'],
                'section_image_first' => $validated['section_image_first'],
                'has_cta_buttons' => $validated['has_cta_buttons'],
                'section_has_map' => $validated['section_has_map'],
                'sliding_hero_section' => $validated['sliding_hero_section'],
                'section_has_bg' => $validated['section_has_bg'],
                'section_background_type' => $validated['section_background_type'],
                'section_background_color' => $validated['section_background_color'],
                'section_style' => $validated['section_style'],
                'width_style' => $validated['width_style'],
                'width' => $validated['width'],
                'form_title' => $validated['form_title'],
                'form_sub_title' => $validated['form_sub_title'],
                'form_button_text' => $validated['form_button_text'],
                'map_link' => $validated['map_link'],
                'video_link' => $validated['video_link'],
            ]);
            
            if($validated['has_cta_buttons'] === false) {
                $section->cta_buttons()->delete();
            }
            if($validated['section_has_image'] === false) {
                $section->clearMediaCollection('section_image');
            }
            if($validated['section_has_bg'] === false) {
                $section->clearMediaCollection('section_bg');
            }
            if($validated['section_has_bg'] === true && $validated['section_background_type'] === 'color-bg') {
                $section->clearMediaCollection('section_bg');
            }
            
            if($validated['type'] === 'section-ecommerce-product-category' && isset($validated['category_id'])) {
                SectionLink::updateOrCreate([
                        'section_id'     => $section->id,
                    ],
                    [
                        'linkable_type'  => \App\Models\Ecommerce\EcmCategory::class,
                        'linkable_id'    => $validated['category_id'],
                    ]);
            }
            
            DB::commit();
            return back(303);
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Error: ' . $exception->getMessage());
            report($exception);
            return redirect()->back()->withInput()->withErrors(['message' => 'Failed to save page sections. Please try again.']);
        }
    }
    
    public function destroy($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        
        foreach ($section->pricing_plans as $sectionPlan) {
            $plan = PricingPlan::find($sectionPlan->id);
            
            if ($plan) {
                $plan->features()->delete();
                $plan->delete();
            }
        }
        
        $section->cta_buttons()->delete();
        $section->section_cards()->delete();
        $section->hero_slides()->delete();
        $section->icon_boxes()->delete();
        $section->faqs()->delete();
        $section->testimonials()->delete();
        
        $section->clearMediaCollection('section_image');
        $section->clearMediaCollection('section_bg');
        $section->clearMediaCollection('section-gallery');
        
        $section->delete();
        
        return back(303);
    }
    
    public function updateOrder(Request $request)
    {
        $sections = $request->input('sections', []);
        
        foreach ($sections as $section) {
            Section::where('id', $section['id'])
                ->update(['order' => $section['order']]);
        }
        
        return back(303)->with(['message' => 'EcmOrder updated successfully']);
    }
    
    public function uploadSectionImage(Request $request, $section)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $section = Section::findOrFail($section);
        
        if($request->hasFile('file')) {
            $section->clearMediaCollection('section_image');
            $section->addMedia($validated['file'])
                ->toMediaCollection('section_image');
        }
        
        return back(303);
    }
    
    public function deleteSectionImage(Request $request, $section)
    {
        $section = Section::findOrFail($section);
        $section->clearMediaCollection('section_image');
        
        return back(303)->with('success', 'Section image deleted successfully.');
    }
    
    public function uploadGallery(Request $request, $section)
    {
        $section = Section::findOrFail($section);
        $request->validate([
            'file' => 'required|image|max:10240', // 10MB
        ]);
        
        $media = $section->addMedia($request->file('file'))
            ->toMediaCollection('section-gallery');
        
        return response()->json([
            'id' => $media->id,
            'url' => $media->getUrl(),
            'name' => $media->name,
        ]);
    }
    
    public function deleteGalleryMedia($mediaId, $section)
    {
        $section = Section::findOrFail($section);
        $media = $section->getMedia('section-gallery')->find($mediaId);
        
        if ($media) {
            $media->delete();
            return back(303)->with('message', 'Media deleted successfully');
        }
        
        return back()->with('message', 'Media not found');
    }
    
    public function moveSection(Request $request)
    {
        $validated = $request->validate([
            'page_id' => ['required'],
            'section_id' => ['required'],
            'new_page_id' => ['required']
        ]);
        
        $section = Section::findOrFail($validated['section_id']);
        
        $section->update([
            'page_id' => $validated['new_page_id'],
        ]);
        
        return back()->with('message', 'Section moved successfully');
    }
}
