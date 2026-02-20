<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'decision_id' => $this->decision_id,
            'was_successful' => $this->was_successful,
            'outcome_notes' => $this->outcome_notes,
            'reviewed_at' => $this->reviewed_at?->toISOString(),
            'assumption_evaluations' => $this->whenLoaded('assumptionEvaluations', function () {
                return $this->assumptionEvaluations->map(fn($e) => [
                    'assumption_id' => $e->assumption_id,
                    'was_correct' => $e->was_correct,
                ]);
            }),
        ];
    }
}
