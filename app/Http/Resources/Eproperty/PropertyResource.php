<?php

namespace App\Http\Resources\Eproperty;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hashid' => $this->hashid,
            'user_id' => $this->user_id,
            'property_type_id' => $this->property_type_id,
            'property_category_id' => $this->property_category_id,
            'listing_type_id' => $this->listing_type_id,
            'pricing_model_id' => $this->pricing_model_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            
            'pricing' => [
                'price' => $this->price,
                'formatted_price' => $this->formatted_price,
                'price_range' => $this->price_range,
                'currency_id' => $this->currency_id,
                'negotiable' => $this->negotiable,
            ],
            
            'available_as' => $this->available_as,
            'constituency' => $this->constituency,
            'country' => $this->country,
            'status' => $this->status,
            'featured' => $this->featured,
            'views_count' => $this->views_count,
            'is_verified' => $this->is_verified,
            'verified_by' => $this->verified_by,
            'owner' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'property_type' => $this->whenLoaded('propertyType', fn() => [
                'id' => $this->propertyType->id,
                'name' => $this->propertyType->name,
                'slug' => $this->propertyType->slug,
                'icon' => $this->propertyType->icon,
            ]),
            'property_category' => $this->whenLoaded('propertyCategory', fn() => [
                'id' => $this->propertyCategory->id,
                'name' => $this->propertyCategory->name,
                'slug' => $this->propertyCategory->slug,
                'icon' => $this->propertyCategory->icon,
            ]),
            'listing_type' => $this->whenLoaded('listingType', fn() => [
                'id' => $this->listingType->id,
                'name' => $this->listingType->name,
                'slug' => $this->listingType->slug,
                'icon' => $this->listingType->icon,
            ]),
            'pricing_model' => $this->whenLoaded('pricingModel', fn() => [
                'id' => $this->pricingModel->id,
                'name' => $this->pricingModel->name,
                'slug' => $this->pricingModel->slug,
            ]),
            'currency' => $this->whenLoaded('currency', fn() => [
                'id' => $this->currency->id,
                'name' => $this->currency->name,
                'code' => $this->currency->code,
                'symbol' => $this->currency->symbol,
            ]),
            'details' => $this->whenLoaded('details', fn() => [
                'plot_number' => $this->details->plot_number,
                'town_or_estate' => $this->details->town_or_estate,
                'area_map' => $this->details->area_map,
                'disputes' => $this->details->disputes,
                'ownership_type' => $this->details->ownership_type,
                'land_use' => $this->details->land_use,
                'title_type' => $this->details->title_type,
                'parking_spaces' => $this->details->parking_spaces,
                'property_conditions' => $this->details->property_conditions,
                'furnishing' => $this->details->furnishing,
                'is_title_deed_available' => $this->details->is_title_deed_available,
                'total_area' => $this->details->total_area,
                'built_area' => $this->details->built_area,
                'year_built' => $this->details->year_built,
                'floors' => $this->details->floors,
                'has_units' => $this->details->has_units,
                'is_furnished' => $this->details->is_furnished,
                'address' => [
                    'line_1' => $this->details->address_line_1,
                    'line_2' => $this->details->address_line_2,
                    'city' => $this->details->city,
                    'state' => $this->details->state,
                    'country' => $this->details->country,
                    'postal_code' => $this->details->postal_code,
                    'full_address' => $this->details->full_address,
                ],
                'location' => [
                    'latitude' => $this->details->latitude,
                    'longitude' => $this->details->longitude,
                ],
                'area_size' => $this->details->area_size,
                'area_unit' => $this->details->area_unit,
                'rooms' => [
                    'kitchens' => $this->details->kitchens,
                    'bedrooms' => $this->details->bedrooms,
                    'bathrooms' => $this->details->bathrooms,
                ],
                'airbnb' => [
                    'max_guests' => $this->details->max_guests,
                    'min_nights' => $this->details->min_nights,
                ],
            ]),
//            'primary_image' => $this->primary_image,
            'images' => $this->when(
                $this->relationLoaded('media'),
                fn() => $this->getMedia('property-images')->map(fn($media) => [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'name' => $media->name,
                    'size' => $media->size,
                ])
            ),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
