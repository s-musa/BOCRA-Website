<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionCardRequest;
use App\Http\Resources\Resource;
use App\Models\SectionCard;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SectionCardController extends Controller
{
    public function datatable()
    {
        $cards = QueryBuilder::for(
            SectionCard::with('page', 'section', 'media')->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('section_id'),
            AllowedFilter::exact('page_id'),
        ])->jsonPaginate();
        
        return Resource::collection($cards);
    }
    
    public function store(SectionCardRequest $request)
    {
        $purifier = new HtmlPurifierService();
        $validated = $request->validated();
        
//        dd($validated);
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        $validated['has_link'] = false;
        
        if($validated['card_link_type']) {
            $validated['has_link'] = true;
        }
        
        SectionCard::create([
            'section_id' => $validated['section_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
            'has_link' => $validated['has_link'],
            'card_link_type' => $validated['card_link_type'],
            'custom_url' => $validated['custom_url'] ?? null,
            'section_url' => $validated['section_url'] ?? null,
            'page_id' => $validated['page_id'] ?? null,
        ]);
        
        return back(303)->with('success', 'Section Card created.');
    }
    
    public function update(SectionCardRequest $request, $sectionCard)
    {
        $validated = $request->validated();
        
        $purifier = new HtmlPurifierService();
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        $validated['has_link'] = false;
        
        if($validated['card_link_type']) {
            $validated['has_link'] = true;
        }
        
        $card = SectionCard::findOrFail($sectionCard);
        
        $card->update([
            'section_id' => $validated['section_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
            'has_link' => $validated['has_link'],
            'card_link_type' => $validated['card_link_type'],
            'custom_url' => $validated['custom_url'],
            'section_url' => $validated['section_url'],
            'page_id' => $validated['page_id'],
        ]);
        
        return back(303)->with('success', 'Section Card updated.');
    }
    
    public function destroy($sectionCard)
    {
        $card = SectionCard::findOrFail($sectionCard);
        $card->delete();
        
        return back(303)->with('success', 'Section Card deleted.');
    }
    
    public function uploadSectionCardImage(Request $request, SectionCard $sectionCard)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        if($request->hasFile('file')) {
            $sectionCard->clearMediaCollection('card-image');
            $sectionCard->addMedia($validated['file'])
                ->toMediaCollection('card-image');
        }
        
        return back(303);
    }
}
