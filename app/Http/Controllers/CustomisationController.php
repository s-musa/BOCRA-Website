<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomisationRequest;
use App\Http\Resources\Resource;
use App\Models\Customisation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomisationController extends Controller
{
    public function dataTable()
    {
        return Customisation::with('media')->orderBy('id')->first();
    }
    
    public function index()
    {
        return Inertia::render('Admin/Customisation/Index', []);
    }
    
    public function store(CustomisationRequest $request)
    {
        $validated = $request->validated();
        
        Customisation::create([
            'primary_color' => $validated['primary_color'],
            'primary_color_rgb' => Customisation::hexToRgb($validated['primary_color']),
            'primary_color_light' => $validated['primary_color_light'],
            'primary_color_light_rgb' => Customisation::hexToRgb($validated['primary_color_light']),
            'secondary_color' => $validated['secondary_color'],
            'secondary_color_rgb' => Customisation::hexToRgb($validated['secondary_color']),
            'secondary_color_light' => $validated['secondary_color_light'],
            'secondary_color_light_rgb' => Customisation::hexToRgb($validated['secondary_color_light']),
        ]);
        
        return to_route('admin.customisations.index');
    }
    
    public function update(CustomisationRequest $request, Customisation $customisation)
    {
        $validated = $request->validated();
        
        $customisation->update([
            'primary_color' => $validated['primary_color'],
            'primary_color_rgb' => Customisation::hexToRgb($validated['primary_color']),
            'primary_color_light' => $validated['primary_color_light'],
            'primary_color_light_rgb' => Customisation::hexToRgb($validated['primary_color_light']),
            'secondary_color' => $validated['secondary_color'],
            'secondary_color_rgb' => Customisation::hexToRgb($validated['secondary_color']),
            'secondary_color_light' => $validated['secondary_color_light'],
            'secondary_color_light_rgb' => Customisation::hexToRgb($validated['secondary_color_light']),
        ]);
        
        return back(303);
    }
    
    public function generalCustomisation(Request $request, Customisation $customisation)
    {
        $validated = $request->validate([
            'section_width_style' => ['required', 'string'],
            'section_width' => ['nullable', 'integer', 'min:0'],
        ]);
        
        $customisation->update([
            'section_width_style' => $validated['section_width_style'],
            'section_width' => $validated['section_width'],
        ]);
        
        return back(303);
    }
    
    public function defaults(Customisation $customisation)
    {
        $customisation->update([
            'primary_color' => '#1285e5',
            'primary_color_light' => '#e1eefd',
            'secondary_color' => '#ffd90d',
            'secondary_color_light' => '#fff786',
        ]);
        
        return back(303)->with('Website colors have been reset!');
    }
    
    public function setButtonStyles(Request $request, Customisation $customisation)
    {
        $request->validate([
            'button_style' => ['required']
        ]);
        
        $customisation->update([
            'button_style' => $request->button_style,
        ]);
        
        return back(303)->with('Button style updated successfully.');
    }
    
    public function headerRoute()
    {
        return view('website.index');
    }
    
    public function updateHeaderStyles(Request $request, Customisation $customisation)
    {
        $validated = $request->validate([
            'header_style' => ['nullable', 'string'],
            'header_bg' => ['nullable', 'string'],
            'top_header' => ['required', 'boolean'],
            'top_header_bg' => [
                Rule::requiredIf($request->boolean('top_header')),
                'nullable',
                'string'
            ],
        ]);
        
        $customisation->update([
            'header_style' => $validated['header_style'] ?? 'default',
            'header_bg' => $validated['header_bg'] ?? 'default',
            'top_header' => $validated['top_header'],
            'top_header_bg' => $validated['top_header_bg'] ?? null,
        ]);
        
        return back(303);
    }
    
    public function updateFooterStyles(Request $request, Customisation $customisation)
    {
        $validated = $request->validate([
            'footer_style' => ['nullable', 'string'],
            'footer_bg_color' => ['nullable', 'string'],
            'footer_text' => ['nullable', 'string'],
        ]);
        
        $customisation->update([
            'footer_style' => $validated['footer_style'] ?? null,
            'footer_bg_color' => $validated['footer_bg_color'] ?? null,
            'footer_text' => $validated['footer_text'] ?? null,
        ]);
        
        return back(303);
    }
    
    public function updateBannerStyles(Request $request, Customisation $customisation)
    {
        $validated = $request->validate([
            'banner_style' => ['nullable', 'string'],
            'banner_bg' => ['nullable', 'string'],
        ]);
        
        $customisation->update([
            'banner_style' => $validated['banner_style'] ?? null,
            'banner_bg' => $validated['banner_bg'] ?? null,
        ]);
        
        return back(303);
    }
    
    public function uploadBannerImage(Request $request, $customisation)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $customisation = Customisation::findOrFail($customisation);
        
        if($request->hasFile('file')) {
            $customisation->clearMediaCollection('banner-image');
            $customisation->addMedia($validated['file'])
                ->toMediaCollection('banner-image');
        }
        
        return back(303);
    }
    
    public function deleteBannerImage(Request $request, $customisation)
    {
        $customisation = Customisation::findOrFail($customisation);
        $customisation->clearMediaCollection('banner-image');
        
        return back(303);
    }
}
