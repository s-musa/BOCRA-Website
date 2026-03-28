<?php

namespace App\Http\Resources\Eproperty;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingTypeResource extends JsonResource
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
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'properties_count' => $this->when(
                $this->relationLoaded('properties'),
                fn() => $this->properties->count()
            ),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
