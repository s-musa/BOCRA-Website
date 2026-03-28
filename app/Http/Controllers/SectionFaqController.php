<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionFaqRequest;
use App\Http\Resources\Resource;
use App\Models\SectionFaq;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SectionFaqController extends Controller
{
    public function datatable()
    {
        $sectionFaqs = QueryBuilder::for(
            SectionFaq::with('section')->orderBy('order')->orderBy('created_at')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('section_id'),
        ])->jsonPaginate();
        
        return Resource::collection($sectionFaqs);
    }
    
    public function store(SectionFaqRequest $request)
    {
        $validated = $request->validated();
        $purifier = new HtmlPurifierService();
        
        SectionFaq::create([
            'section_id' => $validated['section_id'],
            'question' => $validated['question'],
            'answer' => $purifier->purify($validated['answer']),
        ]);
        
        return back(303)->with('message', 'Section faq created successfully.');
    }
    
    public function update(SectionFaqRequest $request, SectionFaq $sectionFaq)
    {
        $validated = $request->validated();
        $purifier = new HtmlPurifierService();
        
        $sectionFaq->update([
            'question' => $validated['question'],
            'answer' => $purifier->purify($validated['answer']),
        ]);
        
        return back(303)->with('message', 'Section faq updated successfully.');
    }
    
    public function destroy($sectionFaq)
    {
        $faq = SectionFaq::findOrFail($sectionFaq);
        $faq->delete();
        
        return back(303)->with('message', 'Section faq deleted successfully.');
    }
}
