<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PartnerController extends Controller
{
    public function datatable()
    {
        $partners = QueryBuilder::for(
            Partner::with('media')->orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($partners);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('partners', 'name')],
            'active' => ['required', 'boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);
        
        $partner = Partner::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        if($request->hasFile('media')) {
            $partner->addMedia($validated['media'])
                ->toMediaCollection('partner-image');
        }
        
        return back(303)->with('success', 'Partner added successfully.');
    }
    
    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('partners', 'name')->ignore($partner)],
            'active' => ['required', 'boolean'],
        ]);
        
        $partner->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Partner updated successfully.');
    }
    
    public function destroy(Partner $partner)
    {
        $partner->delete();
        return back(303)->with('success', 'Partner deleted successfully.');
    }
}
