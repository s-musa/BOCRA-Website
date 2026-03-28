<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstTableResource extends JsonResource
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
            'room_id' => $this->room_id,
            'table_number' => $this->table_number,
            'name' => $this->name,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'min_capacity' => $this->min_capacity,
            'type' => $this->type,
            'shape' => $this->shape,
            'location' => $this->location,
            'is_available' => $this->is_available,
            'status' => $this->status,
            'reservation_fee' => ($this->reservation_fee * 0.01),
            'sort_order' => $this->sort_order,
            'metadata' => $this->metadata,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'room' => new RstRoomResource($this->whenLoaded('room')),
            'amenities' => RstAmenityResource::collection($this->whenLoaded('amenities')),
            'pricing_rules' => RstPricingRuleResource::collection($this->whenLoaded('pricingRules')),
            'variant_options' => RstVariantOptionResource::collection($this->whenLoaded('variantOptions')),
            'time_slots' => RstTimeSlotResource::collection($this->whenLoaded('timeSlots')),
            'blocked_dates' => RstBlockedDateResource::collection($this->whenLoaded('blockedDates')),
            'bookings_count' => $this->when(isset($this->bookings_count), $this->bookings_count),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
