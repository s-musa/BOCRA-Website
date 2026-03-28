<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstRoomResource extends JsonResource
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
            'restaurant_id' => $this->restaurant_id,
            'room_type_id' => $this->room_type_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'min_capacity' => $this->min_capacity,
            'pricing' => [
                'price_per_hour' => ($this->price_per_hour * 0.01),
                'price_per_day' => ($this->price_per_day * 0.01),
                'booking_deposit' => ($this->booking_deposit * 0.01),
            ],
            'area_sqm' => (float) $this->area_sqm,
            'is_available' => $this->is_available,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'metadata' => $this->metadata,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'type' => $this->whenLoaded('type'),
            'media' => $this->when(
                $this->relationLoaded('media'),
                fn() => $this->getMedia('room-images')->map(fn($media) => [
                    'id' => $media->id,
                    'model_id' => $media->model_id,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'name' => $media->name,
                    'size' => $media->size,
                ])
            ),
            'amenities' => RstAmenityResource::collection($this->whenLoaded('amenities')),
            'tables' => RstTableResource::collection($this->whenLoaded('tables')),
            'pricing_rules' => RstPricingRuleResource::collection($this->whenLoaded('pricingRules')),
            'variant_options' => RstVariantOptionResource::collection($this->whenLoaded('variantOptions')),
            'time_slots' => RstTimeSlotResource::collection($this->whenLoaded('timeSlots')),
            'blocked_dates' => RstBlockedDateResource::collection($this->whenLoaded('blockedDates')),
            'bookings_count' => $this->when(isset($this->bookings_count), $this->bookings_count),
            'tables_count' => $this->when(isset($this->tables_count), $this->tables_count),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
