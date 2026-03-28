<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstBlockedDateResource extends JsonResource
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
            'restaurant_id' => $this->restaurant_id,
            'blockable_type' => $this->blockable_type ? class_basename($this->blockable_type) : null,
            'blockable_id' => $this->blockable_id,
            'blocked_date' => $this->blocked_date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'reason' => $this->reason,
            'is_recurring' => $this->is_recurring,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'blockable' => $this->when(
                $this->relationLoaded('blockable'),
                fn() => $this->blockable_type === 'App\Models\Restaurant\RstRoom'
                    ? new RstRoomResource($this->blockable)
                    : new RstTableResource($this->blockable)
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
