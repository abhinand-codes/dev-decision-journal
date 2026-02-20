<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DecisionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'context' => $this->context,
            'chosen_option' => $this->chosen_option,
            'confidence_score' => $this->confidence_score,
            'review_date' => $this->review_date?->toDateString(),
            'status' => $this->status,
            'created_at' => $this->created_at->toISOString(),
            'options' => DecisionOptionResource::collection($this->whenLoaded('options')),
            'assumptions' => AssumptionResource::collection($this->whenLoaded('assumptions')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'latest_review' => new ReviewResource($this->whenLoaded('latestReview')),
        ];
    }
}
