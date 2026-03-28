<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use App\Models\Company;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Section;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function destroy(Request $request, Media $media)
    {
        $media->delete();
        
        return back(303);
    }
    
    public function deleteLogo($companyId)
    {
        $company = Company::findOrFail($companyId);
        $company->clearMediaCollection('logo');
        
        return back(303);
    }
    
    public function deleteFooterLogo($companyId)
    {
        $company = Company::findOrFail($companyId);
        $company->update(['has_footer_logo' => false]);
        $company->clearMediaCollection('footer-logo');
        
        return back(303);
    }
    
    public function deleteFavicon($companyId)
    {
        $company = Company::findOrFail($companyId);
        $company->clearMediaCollection('favicon');
        
        return back(303);
    }
    
    public function uploadServiceImage(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $service = Service::findOrFail($validated['service_id']);
        
        if($request->hasFile('file')) {
            $service->clearMediaCollection('service-image');
            $service->addMedia($validated['file'])
                ->toMediaCollection('service-image');
        }
        
        return back(303);
    }
    
    public function uploadProductImage(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $project = Product::findOrFail($validated['project_id']);
        
        if($request->hasFile('file')) {
            $project->clearMediaCollection('product-image');
            $project->addMedia($validated['file'])
                ->toMediaCollection('product-image');
        }
        
        return back(303);
    }
    
    public function uploadBoardMemberImage(Request $request)
    {
        $validated = $request->validate([
            'board_member_id' => 'required|exists:board_members,id',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $member = BoardMember::findOrFail($validated['board_member_id']);
        
        if($request->hasFile('file')) {
            $member->clearMediaCollection('board-image');
            $member->addMedia($validated['file'])
                ->toMediaCollection('board-image');
        }
        
        return back(303);
    }
    
    public function uploadPartnerImage(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $partner = Partner::findOrFail($validated['partner_id']);
        
        if($request->hasFile('file')) {
            $partner->clearMediaCollection('partner-image');
            $partner->addMedia($validated['file'])
                ->toMediaCollection('partner-image');
        }
        
        return back(303);
    }
    
    public function uploadSectionMedia(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $section = Section::findOrFail($validated['section_id']);
        
        if($request->hasFile('file')) {
            $section->clearMediaCollection('section_image');
            $section->addMedia($validated['file'])
                ->toMediaCollection('section_image');
        }
        
        return back(303);
    }
    
    public function uploadSectionBackgroundMedia(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $section = Section::findOrFail($validated['section_id']);
        $section->update([
            'section_background_type' => 'image-bg',
        ]);
        
        if($request->hasFile('file')) {
            $section->clearMediaCollection('section_bg');
            $section->addMedia($validated['file'])
                ->toMediaCollection('section_bg');
        }
        
        return back(303);
    }
    
    public function deleteSectionMedia($sectionId)
    {
        $section = Section::find($sectionId);
        $section->clearMediaCollection('section_image');
        
        return back(303);
    }
    
    public function deleteSectionBackgroundMedia($sectionId)
    {
        $section = Section::find($sectionId);
        $section->clearMediaCollection('section_bg');
        
        return back(303);
    }
}
