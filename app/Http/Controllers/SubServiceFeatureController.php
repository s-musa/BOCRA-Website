<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubServiceFeatureRequest;
use App\Http\Resources\Resource;
use App\Models\SubServiceFeature;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubServiceFeatureController extends Controller
{
    public function datatable()
    {
        $subServiceFeatures = QueryBuilder::for(
            SubServiceFeature::with('sub_service')->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('sub_service_id'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($subServiceFeatures);
    }
    
    public function store(SubServiceFeatureRequest $request)
    {
        $validated = $request->validated();
        
        SubServiceFeature::create([
            'sub_service_id' => $validated['sub_service_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
        ]);
        
        return back(303);
    }
    
    public function update(SubServiceFeatureRequest $request, SubServiceFeature $subServiceFeature)
    {
        $validated = $request->validated();
        
        $subServiceFeature->update([
            'sub_service_id' => $validated['sub_service_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
        ]);
        
        return back(303);
    }
    
    public function destroy(SubServiceFeature $subServiceFeature)
    {
        $subServiceFeature->delete();
        
        return back(303);
    }
}
