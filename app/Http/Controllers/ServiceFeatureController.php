<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceFeatureRequest;
use App\Http\Resources\Resource;
use App\Models\Service;
use App\Models\ServiceFeature;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceFeatureController extends Controller
{
    public function datatable()
    {
        $serviceFeatures = QueryBuilder::for(
            ServiceFeature::with('service')->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('service_id'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($serviceFeatures);
    }
    
    public function store(ServiceFeatureRequest $request)
    {
        $validated = $request->validated();
        
        ServiceFeature::create([
            'service_id' => $validated['service_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
        ]);
        
        return back(303);
    }
    
    public function update(ServiceFeatureRequest $request, $serviceFeatureId)
    {
        $validated = $request->validated();
        
        $serviceFeature = ServiceFeature::findOrFail($serviceFeatureId);
        
        $serviceFeature->update([
            'service_id' => $validated['service_id'],
            'title' => $validated['title'],
            'icon' => $validated['icon'],
            'details' => $validated['details'],
        ]);
        
        return back(303);
    }
    
    public function destroy(ServiceFeature $serviceFeature)
    {
        $serviceFeature->delete();
        
        return back(303);
    }
}
