<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstBookingResource extends JsonResource
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
            'booking_number' => $this->booking_number,
            'restaurant_id' => $this->restaurant_id,
            'user_id' => $this->user_id,
            'bookable_type' => class_basename($this->bookable_type),
            'bookable_id' => $this->bookable_id,
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'guest_count' => $this->guest_count,
            'booking_date' => $this->booking_date?->toDateString(),
            'booking_time' => $this->booking_time,
            'end_time' => $this->end_time,
            'duration_hours' => $this->duration_hours,
            'booking_type_id' => $this->booking_type_id,
            'special_requests' => $this->special_requests,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'deposit_amount' => ($this->deposit_amount * 0.01),
            'total_amount' => ($this->total_amount * 0.01),
            'confirmed_at' => $this->confirmed_at?->toISOString(),
            'cancelled_at' => $this->cancelled_at?->toISOString(),
            'cancellation_reason' => $this->cancellation_reason,
            'staff_notes' => $this->when($request->user()?->can('view-staff-notes'), $this->staff_notes),
            'metadata' => $this->metadata,
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'bookable' => $this->when(
                $this->relationLoaded('bookable'),
                fn() => $this->bookable_type === 'App\Models\Restaurant\RstRoom'
                    ? new RstRoomResource($this->bookable)
                    : new RstTableResource($this->bookable)
            ),
            'variant_options' => RstBookingVariantOptionResource::collection($this->whenLoaded('variantOptions')),
            'pricing_breakdown' => new RstBookingPricingBreakdownResource($this->whenLoaded('pricingBreakdown')),
            'review' => new RstReviewResource($this->whenLoaded('review')),
            'cancelled_by' => $this->whenLoaded('cancelledBy', fn() => [
                'id' => $this->cancelledBy?->id,
                'name' => $this->cancelledBy?->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
