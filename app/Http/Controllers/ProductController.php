<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Resource;
use App\Models\Product;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function datatable()
    {
        $products = QueryBuilder::for(
            Product::with('media', 'type')->orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($products);
    }
    
    public function store(ProductRequest $request, HtmlPurifierService $purifier)
    {
        $validated = $request->validated();
        
        $validated['slug'] = Str::of($validated['name'])->slug('-');
        if(!empty($validated['details'])) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        $product = Product::create([
            'name' => $validated['name'],
            'product_type_id' => $validated['product_type_id'],
            'slug' => $validated['slug'],
            'details' => $validated['details'] ?? null,
            'active' => $validated['active'],
        ]);
        
        if($request->hasFile('media')) {
            $product->addMedia($validated['media'])
                ->toMediaCollection('product-image');
        }
        
        return back(303)->with('success', 'Product created successfully.');
    }

//    public function show($product)
//    {
//        $product = Product::where('slug', '=', $product)->firstOrFail();
//        $product->load('media', 'type');
//        $otherProducts = Product::where('id', '!=', $product->id)->orderBy('name')->get();
//        $otherProducts->load('media', 'type');
//
//        $seo = $product->metaSeo;
//
//        if($seo) {
//            \App\Models\MetaSeo::applyMeta($seo);
//        }
//
//        return view('website.product-details', [
//            'product' => $product,
//            'otherProducts' => $otherProducts,
//        ]);
//    }
    
    public function update(ProductRequest $request, Product $product, HtmlPurifierService $purifier)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['slug'])->slug('-');
        if(!empty($validated['details'])) {
            $validated['details'] = $purifier->purify($validated['details']);
        }
        
        $product->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'product_type_id' => $validated['product_type_id'],
            'details' => $validated['details'] ?? null,
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Product updated successfully.');
    }
    
    public function destroy($product)
    {
        $product = Product::findOrFail($product);
        $product->delete();
        
        return back(303)->with('success', 'Product deleted successfully.');
    }
}
