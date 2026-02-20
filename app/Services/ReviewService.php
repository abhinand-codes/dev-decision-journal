<?php

namespace App\Services;

use App\Models\Decision;
use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function __construct(
        protected ReviewRepositoryInterface $reviewRepository
    ) {}

    public function submitReview(Decision $decision, array $data): Review
    {
        return DB::transaction(function () use ($decision, $data) {

            $review = $this->reviewRepository->create([
                'decision_id'   => $decision->id,
                'was_successful'=> $data['was_successful'],
                'outcome_notes' => $data['outcome_notes'] ?? null,
                'reviewed_at'   => now(),
            ]);

            if (!empty($data['assumption_evaluations'])) {
                foreach ($data['assumption_evaluations'] as $evaluation) {
                    $review->assumptionEvaluations()->create([
                        'assumption_id' => $evaluation['assumption_id'],
                        'was_correct'   => $evaluation['was_correct'],
                    ]);
                }
            }

            return $review->load('assumptionEvaluations');
        });
    }
}