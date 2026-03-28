<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\PricingPlanFeature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PricingPlanFeatureController extends Controller
{
    public function datatable()
    {
        $features = QueryBuilder::for(
            PricingPlanFeature::orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('is_included'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($features);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('pricing_plan_features', 'name')],
            'is_included' => ['required', 'boolean'],
        ]);
        
        PricingPlanFeature::create([
            'name' => $validated['name'],
            'is_included' => $validated['is_included'],
        ]);
        
        return back(303)->with('success', 'Feature added successfully.');
    }
    
    public function update(Request $request, PricingPlanFeature $pricingPlanFeature)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('pricing_plan_features', 'name')->ignore($pricingPlanFeature)],
            'is_included' => ['required', 'boolean'],
        ]);
        
        $pricingPlanFeature->update([
            'name' => $validated['name'],
            'is_included' => $validated['is_included'],
        ]);
        
        return back(303)->with('success', 'Feature updated successfully.');
    }
    
    public function destroy(PricingPlanFeature $pricingPlanFeature)
    {
        $pricingPlanFeature->delete();
        return back(303)->with('success', 'Feature deleted successfully.');
    }
}
