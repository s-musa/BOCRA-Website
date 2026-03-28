<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductTypeController extends Controller
{
    public function datatable()
    {
        $productTypes = QueryBuilder::for(
            ProductType::orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($productTypes);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('product_types', 'name')],
            'active' => ['required', 'boolean'],
        ]);
        
        ProductType::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Product Type added successfully.');
    }
    
    public function update(Request $request, ProductType $productType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('product_types', 'name')->ignore($productType)],
            'active' => ['required', 'boolean'],
        ]);
        
        $productType->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Product type updated successfully.');
    }
    
    public function destroy(ProductType $productType)
    {
        $productType->delete();
        return back(303)->with('success', 'Product type deleted successfully.');
    }
}
