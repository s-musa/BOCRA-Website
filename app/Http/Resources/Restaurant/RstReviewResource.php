<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RstReviewResource extends JsonResource
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
            'booking_id' => $this->booking_id,
            'user_id' => $this->user_id,
            'reviewer' => [
                'name' => $this->reviewer_name,
                'email' => $this->when($request->user()?->can('view-reviewer-email'), $this->reviewer_email),
            ],
            'rating' => $this->rating,
            'detailed_ratings' => [
                'food' => $this->food_rating,
                'service' => $this->service_rating,
                'ambiance' => $this->ambiance_rating,
                'value' => $this->value_rating,
            ],
            'average_detailed_rating' => $this->when(
                $this->food_rating || $this->service_rating || $this->ambiance_rating || $this->value_rating,
                fn() => $this->getAverageRating()
            ),
            'comment' => $this->comment,
            'images' => $this->images ? collect($this->images)->map(fn($img) => asset('storage/' . $img)) : [],
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'approved_at' => $this->approved_at?->toISOString(),
            'restaurant' => new RstRestaurantResource($this->whenLoaded('restaurant')),
            'booking' => new RstBookingResource($this->whenLoaded('booking')),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
            'approved_by' => $this->whenLoaded('approvedBy', fn() => [
                'id' => $this->approvedBy?->id,
                'name' => $this->approvedBy?->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
