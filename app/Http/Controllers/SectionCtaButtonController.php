<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\Section;
use App\Models\SectionCtaButton;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SectionCtaButtonController extends Controller
{
    public function datatable()
    {
        $ctaButtons = QueryBuilder::for(
            SectionCtaButton::with('section', 'page')->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('section_id'),
            AllowedFilter::exact('page_id'),
        ])->jsonPaginate();
        
        return Resource::collection($ctaButtons);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'cta_link_type' => ['required', 'string'],
            'custom_url' => ['nullable', 'string'],
            'section_url' => ['nullable', 'string'],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
            'cta_button_text' => ['required', 'string'],
            'cta_button_type' => ['nullable', 'string'],
        ]);
        
        $section = Section::find($validated['section_id']);
        if($section->has_cta_buttons === false) {
            $section->update([
                'has_cta_buttons' => true,
            ]);
        }
        
        SectionCtaButton::create([
            'section_id' => $validated['section_id'],
            'cta_link_type' => $validated['cta_link_type'],
            'custom_url' => $validated['custom_url'],
            'section_url' => $validated['section_url'],
            'page_id' => $validated['page_id'],
            'cta_button_text' => $validated['cta_button_text'],
            'cta_button_type' => $validated['cta_button_type'],
        ]);
        
        return back(303);
    }
    
    public function update(Request $request, $sectionCtaButton)
    {
        $sectionCtaButton = SectionCtaButton::find($sectionCtaButton);
        
        $validated = $request->validate([
            'cta_link_type' => ['required', 'string'],
            'custom_url' => ['nullable', 'string'],
            'section_url' => ['nullable', 'string'],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
            'cta_button_text' => ['nullable', 'string'],
            'cta_button_type' => ['nullable', 'string'],
        ]);
        
        $section = Section::find($sectionCtaButton->section_id);
        
        if($section->has_cta_buttons === false) {
            $section->update([
                'has_cta_buttons' => true,
            ]);
        }
        
        $sectionCtaButton->update([
            'cta_link_type' => $validated['cta_link_type'],
            'custom_url' => $validated['custom_url'],
            'section_url' => $validated['section_url'],
            'page_id' => $validated['page_id'],
            'cta_button_text' => $validated['cta_button_text'],
            'cta_button_type' => $validated['cta_button_type'],
        ]);
        
        return back(303);
    }
    
    public function destroy($sectionCtaButtonId)
    {
//        dd($sectionCtaButtonId);
        $sectionCtaButton = SectionCtaButton::findOrFail($sectionCtaButtonId);
        $sectionCtaButton->delete();
        
        return back(303);
    }
}
