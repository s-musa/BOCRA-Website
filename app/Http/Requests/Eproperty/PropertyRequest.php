<?php

namespace App\Http\Requests\Eproperty;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->getMethod() == 'POST') {
            $this->createRules();
        }
        
        return $this->updateRules();
    }
    
    public function createRules(): array
    {
        return [
            // Basic Information
            'property_type_id' => 'required|exists:property_types,id',
            'property_category_id' => 'required|exists:property_categories,id',
            'listing_type_id' => 'required|exists:listing_types,id',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            
            // Pricing & Availability
            'pricing_model_id' => 'required|exists:pricing_models,id',
            'price' => 'required|numeric|min:0',
            'price_range' => 'nullable|string|max:100',
            'currency_id' => 'required|exists:currencies,id',
            'negotiable' => 'nullable',
            'available_as' => 'nullable|string|max:255',
            'status' => 'required|in:available,pending,sold,rented,inactive',
            'featured' => 'nullable',
            
            // Property Details
            'plot_number' => 'nullable|string|max:255',
            'town_or_estate' => 'nullable|string|max:255',
            'area_map' => 'nullable|string|max:255',
            'disputes' => 'nullable|string',
            'ownership_type' => 'nullable|string|max:100',
            'land_use' => 'nullable|string|max:100',
            'title_type' => 'nullable|string|max:100',
            'parking_spaces' => 'nullable|string|max:50',
            'property_conditions' => 'nullable|string|max:100',
            'furnishing' => 'nullable|string|max:100',
            'is_title_deed_available' => 'nullable',
            'total_area' => 'nullable|numeric|min:0',
            'built_area' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'floors' => 'nullable|integer|min:0',
            'has_units' => 'nullable',
            'is_furnished' => 'nullable',
            
            // Location & Address
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area_size' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|in:sqft,sqm,acres,hectares',
            'kitchens' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'max_guests' => 'nullable|integer|min:0',
            'min_nights' => 'nullable|integer|min:0',
            
            // Gallery
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ];
    }
    
    public function updateRules(): array
    {
        return [
            // Basic Information
            'property_type_id' => 'required|exists:property_types,id',
            'property_category_id' => 'required|exists:property_categories,id',
            'listing_type_id' => 'required|exists:listing_types,id',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            
            // Pricing & Availability
            'pricing_model_id' => 'required|exists:pricing_models,id',
            'price' => 'required|numeric|min:0',
            'price_range' => 'nullable|string|max:100',
            'currency_id' => 'required|exists:currencies,id',
            'negotiable' => 'nullable',
            'available_as' => 'nullable|string|max:255',
            'status' => 'required|in:available,pending,sold,rented,inactive',
            'featured' => 'nullable',
            
            // Property Details
            'plot_number' => 'nullable|string|max:255',
            'town_or_estate' => 'nullable|string|max:255',
            'area_map' => 'nullable|string|max:255',
            'disputes' => 'nullable|string',
            'ownership_type' => 'nullable|string|max:100',
            'land_use' => 'nullable|string|max:100',
            'title_type' => 'nullable|string|max:100',
            'parking_spaces' => 'nullable|string|max:50',
            'property_conditions' => 'nullable|string|max:100',
            'furnishing' => 'nullable|string|max:100',
            'is_title_deed_available' => 'nullable',
            'total_area' => 'nullable|numeric|min:0',
            'built_area' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'floors' => 'nullable|integer|min:0',
            'has_units' => 'nullable',
            'is_furnished' => 'nullable',
            
            // Location & Address
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'constituency' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area_size' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|in:sqft,sqm,acres,hectares',
            'kitchens' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'max_guests' => 'nullable|integer|min:0',
            'min_nights' => 'nullable|integer|min:0',
            
            // Gallery
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ];
    }
}
