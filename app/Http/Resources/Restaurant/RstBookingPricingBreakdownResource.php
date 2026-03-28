<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstBookingPricingBreakdownResource extends JsonResource
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
            'booking_id' => $this->booking_id,
            'base_price' => ($this->base_price * 0.01),
            'subtotal' => ($this->subtotal * 0.01),
            'variant_options_total' => ($this->variant_options_total * 0.01),
            'tax_amount' => ($this->tax_amount * 0.01),
            'service_charge' => ($this->service_charge * 0.01),
            'discount_amount' => ($this->discount_amount * 0.01),
            'total_amount' => ($this->total_amount * 0.01),
            'applied_pricing_rules' => $this->applied_pricing_rules,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
