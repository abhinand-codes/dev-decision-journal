<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'was_successful' => (bool) $this->was_successful,
            'outcome_notes' => $this->outcome_notes,
            'reviewed_at' => $this->reviewed_at?->toISOString(),
        ];
    }
}
