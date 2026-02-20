<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DecisionResource extends JsonResource
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
            'title' => $this->title,
            'context' => $this->context,
            'chosen_option' => $this->chosen_option,
            'confidence_score' => (int) $this->confidence_score,
            'review_date' => $this->review_date?->format('Y-m-d'),
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),

            // Relationships - only present if loaded via with() or load()
            'review' => ReviewResource::make($this->whenLoaded('review')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
