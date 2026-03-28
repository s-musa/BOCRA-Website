<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstTimeSlotResource extends JsonResource
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
            'slottable_type' => $this->slottable_type ? class_basename($this->slottable_type) : null,
            'slottable_id' => $this->slottable_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'available_days' => $this->available_days,
            'max_bookings' => $this->max_bookings,
            'is_active' => $this->is_active,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'slottable' => $this->when(
                $this->relationLoaded('slottable'),
                fn() => $this->slottable_type === 'App\Models\Restaurant\RstRoom'
                    ? new RstRoomResource($this->slottable)
                    : new RstTableResource($this->slottable)
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
