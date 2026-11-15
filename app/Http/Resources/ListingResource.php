<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $images = $this->relationLoaded('images') ? $this->images : collect();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'price' => $this->price,
            'condition' => $this->condition,
            'location' => $this->location,
            'status' => $this->status,
            'views' => (int) $this->views,
            'image' => $images->first()?->url,
            'images' => $images->pluck('url')->values(),
            'imageItems' => $images
                ->map(fn ($image) => ['id' => $image->id, 'url' => $image->url])
                ->values(),
            'messages' => $this->whenCounted('conversations'),
            'seller' => new UserResource($this->whenLoaded('seller')),
            'createdAt' => $this->created_at?->toIso8601String(),
            'date' => $this->created_at?->format('M j, Y'),
        ];
    }
}
