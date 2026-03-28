<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends Controller
{
    public function datatable()
    {
        $tags = QueryBuilder::for(
            Tag::orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($tags);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('tags', 'name')],
            'active' => ['required', 'boolean'],
        ]);
        
        Tag::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Tag added successfully.');
    }
    
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('tags', 'name')->ignore($tag)],
            'active' => ['required', 'boolean'],
        ]);
        
        $tag->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Tag updated successfully.');
    }
    
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back(303)->with('success', 'Tag deleted successfully.');
    }
}
