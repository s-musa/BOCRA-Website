<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function datatable()
    {
        $categories = QueryBuilder::for(
            Category::orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($categories);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('categories', 'name')],
            'active' => ['required', 'boolean'],
        ]);
        
        Category::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Category added successfully.');
    }
    
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('categories', 'name')->ignore($category)],
            'active' => ['required', 'boolean'],
        ]);
        
        $category->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Category updated successfully.');
    }
    
    public function destroy(Category $category)
    {
        $category->delete();
        return back(303)->with('success', 'Category deleted successfully.');
    }
}
