<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\Resource;
use App\Models\Page;
use App\Models\Service;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceController extends Controller
{
    public function datatable()
    {
        $services = QueryBuilder::for(
            Service::with('media', 'sub_services.features', 'features', 'metaSeo')->orderBy('title')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($services);
    }
    
//    public function index()
//    {
//        return Inertia::render('Admin/Components/Services/Index', []);
//    }
    
    public function store(ServiceRequest $request, HtmlPurifierService $purifier)
    {
        $validated = $request->validated();
        
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        $validated['details'] = $purifier->purify($validated['details']);
        
        $service = Service::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'details' => $validated['details'],
            'short_description' => $validated['short_description'],
            'active' => $validated['active'],
        ]);
        
//        if (isset($validated['features']) && is_array($validated['features'])) {
//            foreach ($validated['features'] as $feature) {
//                $service->features()->create([
//                    'title' => $feature['title'],
//                    'icon' => $feature['icon'],
//                    'details' => $feature['details'],
//                ]);
//            }
//        }
        
        if($request->hasFile('media')) {
            $service->addMedia($validated['media'])
                ->toMediaCollection('service-image');
        }
        
        return back(303);
    }
    
    public function show($service)
    {
        $service = Service::where('slug', '=', $service)->firstOrFail();
        $service->load('media', 'sub_services.features', 'features');
        
        $otherServices = Service::with('media')
            ->where('id', '!=', $service->id)
            ->orderBy('title')
            ->get();
        
        return view('website.service-details', [
            'service' => $service,
            'otherServices' => $otherServices,
        ]);
    }
    
    public function update(ServiceRequest $request, Service $service, HtmlPurifierService $purifier)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['slug'])->slug('-');
        $validated['details'] = $purifier->purify($validated['details']);
        
        $service->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'details' => $validated['details'],
            'short_description' => $validated['short_description'],
            'active' => $validated['active'],
        ]);
        
        return back(303);
    }
    
    public function destroy($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->delete();
        
        return redirect()->route('admin.components.index');
    }
    
    public function uploadServiceImage(Request $request, $service)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $service = Service::findOrFail($service);
        
        if($request->hasFile('file')) {
            $service->clearMediaCollection('service-image');
            $service->addMedia($validated['file'])
                ->toMediaCollection('service-image');
        }
        
        return back(303);
    }
    
    public function deleteServiceImage(Request $request, $service)
    {
        $service = Service::findOrFail($service);
        $service->clearMediaCollection('service-image');
        
        return back(303)->with('success', 'Service image deleted successfully.');
    }
    
    public function uploadServiceGallery(Request $request, $service)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $service = Service::findOrFail($service);
        
        if($request->hasFile('file')) {
            $service->addMedia($validated['file'])
                ->toMediaCollection('service-gallery');
        }
        
        return back(303);
    }
    
    public function deleteMedia($service, $mediaId)
    {
        $service = Service::findOrFail($service);
        $media = $service->getMedia('service-gallery')->find($mediaId);
        
        if ($media) {
            $media->delete();
            return back(303)->with('message', 'Media deleted successfully');
        }
        
        return back()->with('message', 'Media not found');
    }
    
    public function storeSeo(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'keywords' => ['required'],
        ]);
        
        if ($service->metaSeo) {
            $service->metaSeo->update($validated);
        } else {
            $service->metaSeo()->create($validated);
        }
        
        return back(303)->with('message', 'Service SEO captured');
    }
}
