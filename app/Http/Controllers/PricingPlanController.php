<?php

namespace App\Http\Controllers;

use App\Helpers\MoneyHelper;
use App\Http\Requests\PricingPlanRequest;
use App\Http\Resources\Resource;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PricingPlanController extends Controller
{
    public function datatable()
    {
        $plans = QueryBuilder::for(
            PricingPlan::with('features', 'page')->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($plans);
    }
    
    public function store(PricingPlanRequest $request)
    {
        $validated = $request->validated();
        
        $validated['slug'] = Str::slug($validated['name']);
        
        PricingPlan::createNewPricingPlan($validated);
        
        return back(303)
            ->with('success', 'Pricing plan created successfully.');
    }
    
    public function update(PricingPlanRequest $request, PricingPlan $pricingPlan)
    {
        $validated = $request->validated();
        
        $validated['slug'] = Str::slug($validated['name']);
        
        PricingPlan::updatePricingPlan($pricingPlan, $validated);
        
        return back(303)
            ->with('success', 'Pricing plan updated successfully.');
    }
    
    public function destroy(PricingPlan $pricingPlan)
    {
        $pricingPlan->features()->delete();
        $pricingPlan->delete();
        
        return back(303)
            ->with('success', 'Pricing plan deleted successfully.');
    }
}
