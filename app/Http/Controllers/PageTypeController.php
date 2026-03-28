<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\PageType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PageTypeController extends Controller
{
    public function dataTable()
    {
        $types = QueryBuilder::for(
            PageType::with('pages')->orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($types);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('page_types', 'name')],
            'active' => ['required', 'boolean'],
        ]);
        
        PageType::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Page Type added successfully.');
    }
    
    public function update(Request $request, PageType $pageType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('page_types', 'name')->ignore($pageType)],
            'active' => ['required', 'boolean'],
        ]);
        
        $pageType->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Page type updated successfully.');
    }
    
    public function destroy(PageType $pageType)
    {
        $pageType->delete();
        return back(303)->with('success', 'Page type deleted successfully.');
    }
}
