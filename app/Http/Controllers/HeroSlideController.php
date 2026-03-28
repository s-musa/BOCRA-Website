<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\HeroSlide;
use App\Models\Section;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class HeroSlideController extends Controller
{
    public function datatable()
    {
        $heroSlides = QueryBuilder::for(
            HeroSlide::with('section', 'page')->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('section_id'),
            AllowedFilter::exact('page_id'),
        ])->jsonPaginate();
        
        return Resource::collection($heroSlides);
    }
    
    public function store(Request $request)
    {
        $purifierService = new HtmlPurifierService();
        $validated = $request->validate([
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
            'title' => ['nullable', 'string'],
            'sub_title' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'string'],
            'cta_button_type' => ['nullable', 'string'],
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5020'],
        ]);
        $details = null;
        if($validated['details']) {
            $details = $purifierService->purify($validated['details']) ?? null;
        }
        
        $section = Section::find($validated['section_id']);
        if($section->sliding_hero_section === false) {
            $section->update([
                'sliding_hero_section' => true,
            ]);
        }
        
        $slide = HeroSlide::create([
            'section_id' => $validated['section_id'],
            'page_id' => $validated['page_id'],
            'title' => $validated['title'],
            'sub_title' => $validated['sub_title'],
            'details' => $details,
            'cta_button_text' => $validated['cta_button_text'],
            'cta_button_type' => $validated['cta_button_type'],
        ]);
        
        if($request->hasFile('file')) {
            $file = $validated['file'];
            
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = Str::slug($originalName);
            $extension = $file->getClientOriginalExtension();
            
            $slide->addMedia($file)
                ->usingName($cleanName)
                ->usingFileName($cleanName . '.' . $extension)
                ->toMediaCollection('slide_image');
        }
        
        return back(303);
    }
    
    public function update(Request $request, HeroSlide $heroSlide)
    {
        $purifierService = new HtmlPurifierService();
        $validated = $request->validate([
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
            'title' => ['nullable', 'string'],
            'sub_title' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'string'],
            'cta_button_type' => ['nullable', 'string'],
        ]);
        $details = null;
        if($validated['details']) {
            $details = $purifierService->purify($validated['details']) ?? null;
        }
        
        $section = Section::find($validated['section_id']);
        if($section->sliding_hero_section === false) {
            $section->update([
                'sliding_hero_section' => true,
            ]);
        }
        
        $heroSlide->update([
            'section_id' => $validated['section_id'],
            'page_id' => $validated['page_id'],
            'title' => $validated['title'],
            'sub_title' => $validated['sub_title'],
            'details' => $details,
            'cta_button_text' => $validated['cta_button_text'],
            'cta_button_type' => $validated['cta_button_type'],
        ]);
        
//        if($request->hasFile('media')) {
//            $heroSlide->clearMediaCollection('slide_image');
//            $file = $validated['media'];
//
//            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
//            $cleanName = Str::slug($originalName);
//            $extension = $file->getClientOriginalExtension();
//
//            $heroSlide->addMedia($file)
//                ->usingName($cleanName)
//                ->usingFileName($cleanName . '.' . $extension)
//                ->toMediaCollection('slide_image');
//        }
        
        return back(303);
    }
    
    public function destroy($heroSlide)
    {
        $slide = HeroSlide::find($heroSlide);
        $slide->delete();
        
        return back(303);
    }
    
    public function uploadSlideImage(Request $request, $heroSlide)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $slide = HeroSlide::findOrFail($heroSlide);
        
        if($request->hasFile('file')) {
            $slide->clearMediaCollection('slide_image');
            $slide->addMedia($validated['file'])
                ->toMediaCollection('slide_image');
        }
        
        return back(303);
    }
    
    public function deleteSlideImage(Request $request, $heroSlide)
    {
        $slide = HeroSlide::findOrFail($heroSlide);
        $slide->clearMediaCollection('slide_image');
        
        return back(303)->with('success', 'Hero Slide image deleted successfully.');
    }
}
