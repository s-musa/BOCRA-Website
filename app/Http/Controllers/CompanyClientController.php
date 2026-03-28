<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\CompanyClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyClientController extends Controller
{
    public function datatable()
    {
        $clients = QueryBuilder::for(
            CompanyClient::with('media')->orderBy('name')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($clients);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('company_clients', 'name')],
            'active' => ['required', 'boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);
        
        $companyClient = CompanyClient::create([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        if($request->hasFile('media')) {
            $companyClient->addMedia($validated['media'])
                ->toMediaCollection('client-image');
        }
        
        return back(303)->with('success', 'Client added successfully.');
    }
    
    public function update(Request $request, CompanyClient $companyClient)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('company_client', 'name')->ignore($companyClient)],
            'active' => ['required', 'boolean'],
        ]);
        
        $companyClient->update([
            'name' => $validated['name'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Client updated successfully.');
    }
    
    public function destroy(CompanyClient $companyClient)
    {
        $companyClient->delete();
        return back(303)->with('success', 'Client deleted successfully.');
    }
    
    public function uploadImage(Request $request, $clientId)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $client = CompanyClient::findOrFail($clientId);
        
        if($request->hasFile('file')) {
            $client->clearMediaCollection('client-image');
            $client->addMedia($validated['file'])
                ->toMediaCollection('client-image');
        }
        
        return back(303);
    }
}
