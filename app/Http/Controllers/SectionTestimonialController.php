<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionTestimonialRequest;
use App\Http\Resources\Resource;
use App\Models\SectionTestimonial;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SectionTestimonialController extends Controller
{
    public function dataTable()
    {
        $testimonials = QueryBuilder::for(
            SectionTestimonial::with('section')
                ->orderBy('order')
                ->orderBy('created_at')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('section_id'),
        ])->jsonPaginate();
        
        return Resource::collection($testimonials);
    }
    
    public function store(SectionTestimonialRequest $request)
    {
        $validated = $request->validated();
        $purifier = new HtmlPurifierService();
        
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        SectionTestimonial::create([
            'section_id' => $validated['section_id'],
            'name' => $validated['name'],
            'position' => $validated['position'],
            'details' => $validated['details'] ?? null,
            'active' => true,
        ]);
        
        return back(303);
    }
    
    public function update(SectionTestimonialRequest $request, SectionTestimonial $sectionTestimonial)
    {
        $validated = $request->validated();
        $purifier = new HtmlPurifierService();
        
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        $sectionTestimonial->update([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'details' => $validated['details'] ?? null,
            'active' => true,
        ]);
        
        return back(303);
    }
    
    public function destroy(SectionTestimonial $sectionTestimonial)
    {
        $sectionTestimonial->delete();
        
        return back(303);
    }
}
