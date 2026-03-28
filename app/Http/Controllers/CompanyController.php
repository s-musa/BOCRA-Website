<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CompanyController extends Controller
{
    public function dataTable()
    {
        $company = Company::orderBy('id')->first();
        $company->load('media');
        
        return $company;
    }
    
    public function index()
    {
        $company = Company::first();
        $logo = $company->hasMedia('logo') ? $company->getMedia('logo')->sortByDesc('created_at')->first()->getUrl() : null;
        $favicon = $company->hasMedia('favicon') ? $company->getMedia('favicon')->sortByDesc('created_at')->first()->getUrl() : null;
        
        return Inertia::render('Admin/Company/Index', [
            'logo' => $logo,
            'favicon' => $favicon,
        ]);
    }
    
    public function create()
    {
        return Inertia::render('Admin/Company/Create');
    }
    
    public function store(CompanyRequest $request)
    {
        $validated = $request->validated();
        
        DB::beginTransaction();
        
        try {
            Company::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'secondary_email' => $validated['secondary_email'],
                'primary_phone' => $validated['primary_phone'],
                'secondary_phone' => $validated['secondary_phone'],
                'primary_whatsapp' => $validated['primary_whatsapp'],
                'secondary_whatsapp' => $validated['secondary_whatsapp'],
                'country' => $validated['country'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'website' => $validated['website'],
                'physical_address' => $validated['physical_address'],
                'postal_address' => $validated['postal_address'],
                'tax_identification_pin' => $validated['tax_identification_pin'],
                'x_profile' => $validated['x_profile'],
                'fb_profile' => $validated['fb_profile'],
                'ig_profile' => $validated['ig_profile'],
                'linkedin_profile' => $validated['linkedin_profile'],
                'tiktok_profile' => $validated['tiktok_profile'],
                'youtube_profile' => $validated['youtube_profile'],
                'pinterest_profile' => $validated['pinterest_profile'],
                'has_footer_logo' => $validated['has_footer_logo'],
            ]);
            
            DB::commit();
            return to_route('admin.companies.index')->with('success', 'Company created successfully.');
            
        } catch(\Exception $exception) {
            DB::rollBack();
            Log::error('Error: ' . $exception->getMessage());
            report($exception);
            return redirect()->back()->withInput()->withErrors(['message' => $exception->getMessage()]);
        }
    }
    
    public function update(Company $company, CompanyRequest $request)
    {
        $validated = $request->validated();
        
        $company->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'secondary_email' => $validated['secondary_email'],
            'primary_phone' => $validated['primary_phone'],
            'secondary_phone' => $validated['secondary_phone'],
            'primary_whatsapp' => $validated['primary_whatsapp'],
            'secondary_whatsapp' => $validated['secondary_whatsapp'],
            'country' => $validated['country'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'website' => $validated['website'],
            'physical_address' => $validated['physical_address'],
            'postal_address' => $validated['postal_address'],
            'tax_identification_pin' => $validated['tax_identification_pin'],
            'x_profile' => $validated['x_profile'],
            'fb_profile' => $validated['fb_profile'],
            'ig_profile' => $validated['ig_profile'],
            'tiktok_profile' => $validated['tiktok_profile'],
            'linkedin_profile' => $validated['linkedin_profile'],
            'youtube_profile' => $validated['youtube_profile'],
            'pinterest_profile' => $validated['pinterest_profile'],
            'has_footer_logo' => $validated['has_footer_logo'],
        ]);
        
        if($validated['has_footer_logo'] === false){
            $company->clearMediaCollection('footer-logo');
        }
        return back(303)->with('success', 'Company updated successfully.');
    }
    
    public function uploadMedia(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $company = Company::findOrFail($validated['company_id']);
        
        if($request->hasFile('logo')) {
            $company->clearMediaCollection('logo');
            $company->addMedia($validated['logo'])
                ->toMediaCollection('logo');
        }
        
        if($request->hasFile('footer_logo')) {
            $company->update(['has_footer_logo' => true]);
            $company->clearMediaCollection('footer-logo');
            $company->addMedia($validated['footer_logo'])
                ->toMediaCollection('footer-logo');
        }
        
        if($request->hasFile('favicon')) {
            $company->clearMediaCollection('favicon');
            $company->addMedia($validated['favicon'])
                ->toMediaCollection('favicon');
        }
        
        return back(303)->with('success', 'Company media uploaded successfully.');
    }
}
