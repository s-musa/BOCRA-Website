<?php

namespace App\Http\Resources\Eproperty;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyAvailabilityResource extends JsonResource
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
            'property_id' => $this->property_id,
            'date' => $this->date?->toDateString(),
            'is_available' => $this->is_available,
            'price_override' => $this->price_override,
            'effective_price' => $this->effective_price,
            'min_nights' => $this->min_nights,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
