<?php

namespace App\Http\Controllers;

use App\Http\Requests\MetaSeoRequest;
use App\Http\Resources\Resource;
use App\Models\MetaSeo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MetaSeoController extends Controller
{
    public function dataTable()
    {
        $metas = QueryBuilder::for(
            MetaSeo::orderBy('id')
        )->allowedFilters([
            AllowedFilter::partial('title'),
            AllowedFilter::exact('seo_able_type'),
            AllowedFilter::exact('seo_able_id'),
        ])->jsonPaginate();
        
        return Resource::collection($metas);
    }
    
    public function index()
    {
        return Inertia::render('Admin/MetaSeo/Index', []);
    }
    
    public function store(MetaSeoRequest $request)
    {
        $validated = $request->validated();
        
        MetaSeo::create($validated);
        
        return back(303)->with('success', 'Meta SEO has been created.');
    }
    
    public function update(MetaSeoRequest $request, MetaSeo $metaSeo)
    {
        $validated = $request->validated();
        
        $metaSeo->update($validated);
        
        return back(303)->with('success', 'Meta SEO has been updated.');
    }
    
    public function destroy(MetaSeo $metaSeo)
    {
        $metaSeo->delete();
        
        return back(303)->with('success', 'Meta SEO has been deleted.');
    }
}
