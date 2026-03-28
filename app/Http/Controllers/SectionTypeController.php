<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\SectionType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SectionTypeController extends Controller
{
    public function dataTable()
    {
        $types = QueryBuilder::for(
            SectionType::with('sections')->orderBy('name')
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
            'name' => ['required', 'string', Rule::unique('section_types', 'name')],
            'active' => ['required', 'boolean'],
        ]);
        
        SectionType::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Section Type added successfully.');
    }
    
    public function update(Request $request, SectionType $sectionType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('section_types', 'name')->ignore($sectionType)],
            'active' => ['required', 'boolean'],
        ]);
        
        $sectionType->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Section type updated successfully.');
    }
    
    public function destroy(SectionType $sectionType)
    {
        $sectionType->delete();
        return back(303)->with('success', 'Section type deleted successfully.');
    }
}
