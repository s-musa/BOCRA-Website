<?php

namespace App\Http\Resources\Restaurant;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstRestaurantResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'email_primary' => $this->email_primary,
            'email_secondary' => $this->email_secondary,
            'phone_primary' => $this->phone_primary,
            'phone_secondary' => $this->phone_secondary,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'currency_id' => $this->currency_id,
            'opening_time' => Carbon::parse($this->opening_time)->format('H:i'),
            'closing_time' => Carbon::parse($this->closing_time)->format('H:i'),
            'opening_days' => $this->opening_days,
            'seating_capacity' => $this->seating_capacity,
            'average_price_per_person' => (float) $this->average_price_per_person,
            'cuisine_type' => $this->cuisine_type,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'terms_and_conditions' => $this->terms_and_conditions,
            'cancellation_policy' => $this->cancellation_policy,
            'social_links' => $this->social_links,
            'metadata' => $this->metadata,
            'media' => $this->whenLoaded('media'),
            'amenities' => RstAmenityResource::collection($this->whenLoaded('amenities')),
            'rooms' => RstRoomResource::collection($this->whenLoaded('rooms')),
            'tables' => RstTableResource::collection($this->whenLoaded('tables')),
            'reviews' => RstReviewResource::collection($this->whenLoaded('reviews')),
            'pricing_rules' => RstPricingRuleResource::collection($this->whenLoaded('pricingRules')),
            'variant_options' => RstVariantOptionResource::collection($this->whenLoaded('variantOptions')),
            'rooms_count' => $this->when(isset($this->rooms_count), $this->rooms_count),
            'tables_count' => $this->when(isset($this->tables_count), $this->tables_count),
            'bookings_count' => $this->when(isset($this->bookings_count), $this->bookings_count),
            'reviews_count' => $this->when(isset($this->reviews_count), $this->reviews_count),
            'average_rating' => $this->when(
                $this->relationLoaded('reviews'),
                fn() => round($this->reviews->where('status', 'approved')->avg('rating'), 1)
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
