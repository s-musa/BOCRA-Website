<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstVariantOptionResource extends JsonResource
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
            'optionable_type' => $this->optionable_type ? class_basename($this->optionable_type) : null,
            'optionable_id' => $this->optionable_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'price' => ($this->price * 0.01),
            'pricing_type' => $this->pricing_type,
            'quantity_settings' => [
                'max_quantity' => $this->max_quantity,
                'min_quantity' => $this->min_quantity,
            ],
            'is_required' => $this->is_required,
            'is_available' => $this->is_available,
            'is_active' => $this->is_active,
            'availability_schedule' => [
                'days' => $this->available_days,
                'from' => $this->available_from,
                'until' => $this->available_until,
            ],
            'group_name' => $this->group_name,
            'sort_order' => $this->sort_order,
            'depends_on_options' => $this->depends_on_options,
            'excludes_options' => $this->excludes_options,
            'metadata' => $this->metadata,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'optionable' => $this->when(
                $this->relationLoaded('optionable'),
                fn() => $this->optionable_type === 'App\Models\Restaurant\RstRoom'
                    ? new RstRoomResource($this->optionable)
                    : new RstTableResource($this->optionable)
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
