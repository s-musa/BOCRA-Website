<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstPricingRuleResource extends JsonResource
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
            'priceable_type' => $this->priceable_type ? class_basename($this->priceable_type) : null,
            'priceable_id' => $this->priceable_id,
            'name' => $this->name,
            'description' => $this->description,
            'rule_type' => $this->rule_type,
            'adjustment' => [
                'type' => $this->adjustment_type,
                'value' => (float) $this->adjustment_value,
                'is_increase' => $this->is_increase,
            ],
            'date_range' => [
                'start_date' => $this->start_date?->toDateString(),
                'end_date' => $this->end_date?->toDateString(),
            ],
            'applicable_days' => $this->applicable_days,
            'time_range' => [
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ],
            'booking_window' => [
                'days_before_booking' => $this->days_before_booking,
                'type' => $this->booking_window_type,
            ],
            'duration_range' => [
                'min_hours' => $this->min_duration_hours,
                'max_hours' => $this->max_duration_hours,
            ],
            'guest_range' => [
                'min_guests' => $this->min_guests,
                'max_guests' => $this->max_guests,
            ],
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'is_combinable' => $this->is_combinable,
            'conditions' => $this->conditions,
            'metadata' => $this->metadata,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'priceable' => $this->when(
                $this->relationLoaded('priceable'),
                fn() => $this->priceable_type === 'App\Models\Restaurant\RstRoom'
                    ? new RstRoomResource($this->priceable)
                    : new RstTableResource($this->priceable)
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
