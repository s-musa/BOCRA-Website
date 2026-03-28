<?php

namespace App\Http\Controllers;

use App\Http\Requests\IconBoxRequest;
use App\Http\Resources\Resource;
use App\Models\IconBox;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IconBoxController extends Controller
{
    public function datatable()
    {
        $iconBoxes = QueryBuilder::for(
            IconBox::with('section')->orderBy('order')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('section_id'),
            AllowedFilter::exact('page_id'),
        ])->jsonPaginate();
        
        return Resource::collection($iconBoxes);
    }
    
    public function store(IconBoxRequest $request)
    {
        $purifier = new HtmlPurifierService();
        $validated = $request->validated();
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        $validated['ha_link'] = false;
        
        if($validated['icon_link_type']) {
            $validated['has_link'] = true;
        }
        
        IconBox::create([
            'section_id' => $validated['section_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
            'has_link' => $validated['has_link'],
            'icon_link_type' => $validated['icon_link_type'],
            'custom_url' => $validated['custom_url'],
            'section_url' => $validated['section_url'],
            'page_id' => $validated['page_id'],
        ]);
        
        return back(303)->with('success', 'Icon Box created.');
    }
    
    public function update(IconBoxRequest $request, IconBox $iconBox)
    {
        $purifier = new HtmlPurifierService();
        $validated = $request->validated();
        if($validated['details']) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
//        dd($request->all());
        
        $validated['has_link'] = false;
        
        if(isset($validated['card_link_type'])) {
            $validated['has_link'] = true;
        }
        
        $iconBox->update([
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
            'has_link' => $validated['has_link'],
            'icon_link_type' => $validated['card_link_type'],
            'custom_url' => $validated['card_url'] ?? null,
            'section_url' => $validated['section_url'] ?? null,
            'page_id' => $validated['page_id'] ?? null,
        ]);
        
        return back(303)->with('success', 'Icon Box created.');
    }
    
    public function destroy($iconBox)
    {
        $card = IconBox::findOrFail($iconBox);
        $card->delete();
        
        return back(303)->with('success', 'Icon Box deleted.');
    }
}
