<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstBookingVariantOptionResource extends JsonResource
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
            'type' => $this->type,
            'pricing_type' => $this->pricing_type,
            'quantity' => $this->pivot->quantity,
            'unit_price' => ($this->pivot->unit_price * 0.01),
            'total_price' => ($this->pivot->total_price * 0.01),
            'special_instructions' => $this->pivot->special_instructions,
        ];
    }
}
