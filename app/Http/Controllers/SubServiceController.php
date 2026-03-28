<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubServiceRequest;
use App\Http\Resources\Resource;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubServiceController extends Controller
{
    public function datatable()
    {
        $subServices = QueryBuilder::for(
            SubService::with('service', 'media')->orderBy('title')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('service_id'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($subServices);
    }
    
    public function store(SubServiceRequest $request)
    {
        $validated = $request->validated();
        
        $subService = SubService::create([
            'service_id' => $validated['service_id'],
            'title' => $validated['title'],
            'details' => $validated['details'],
            'has_image' => $validated['has_image'],
        ]);
        
        if($request->hasFile('media')) {
            $subService->addMedia($validated['media'])
                ->toMediaCollection('sub-service-image');
        }
        
        return back(303);
    }
    
    public function update(SubServiceRequest $request, $subServiceId)
    {
        dd($request->all());
        $validated = $request->validated();
        
        $subService = SubService::findOrFail($subServiceId);
        
        $subService->update([
            'service_id' => $validated['service_id'],
            'title' => $validated['title'],
            'details' => $validated['details'],
            'has_image' => $validated['has_image'],
        ]);
        
        return back(303);
    }
    
    public function destroy(SubService $subService)
    {
        $subService->clearMediaCollection();
        $subService->delete();
        
        return back(303);
    }
    
    public function uploadSubServiceImage(Request $request, $subServiceId)
    {
        $validated = $request->validate([
            'media' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $subService = SubService::findOrFail($subServiceId);
        
        if($request->hasFile('media')) {
            $subService->clearMediaCollection('sub-service-image');
            $subService->addMedia($validated['media'])
                ->toMediaCollection('sub-service-image');
        }
        
        return back(303);
    }
    
    public function deleteSubServiceImage($subServiceId)
    {
        $subService = SubService::findOrFail($subServiceId);
        $subService->features->each->delete();
        $subService->clearMediaCollection('sub-service-image');
        
        return back(303)->with('success', 'Sub-Service image deleted successfully.');
    }
}
