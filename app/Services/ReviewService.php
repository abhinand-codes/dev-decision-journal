<?php

namespace App\Services;

use App\Models\Decision;
use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * The only statuses from which a review can be submitted.
     * A decision must have been triggered by the scheduler before a review
     * is allowed, preventing accidental reviews on decisions that are still
     * actively being considered.
     */
    private const REVIEWABLE_STATUSES = ['awaiting_review'];

    public function __construct(
        protected ReviewRepositoryInterface $reviewRepository
    ) {
    }

    /**
     * Submit a review for the given decision.
     *
     * State transition: awaiting_review → reviewed
     *
     * @throws \LogicException When the decision is not in a reviewable state.
     */
    public function submitReview(Decision $decision, array $data): Review
    {
        // Centralised guard: reject invalid state transitions up-front so that
        // no other call-site can accidentally review a pending or already-reviewed
        // decision.
        if (!in_array($decision->status, self::REVIEWABLE_STATUSES, true)) {
            throw new \LogicException(
                "Cannot submit a review for decision #{$decision->id}: " .
                "expected status [" . implode(', ', self::REVIEWABLE_STATUSES) . "], " .
                "got '{$decision->status}'."
            );
        }

        return DB::transaction(function () use ($decision, $data) {

            $review = $this->reviewRepository->create([
                'decision_id' => $decision->id,
                'was_successful' => $data['was_successful'],
                'outcome_notes' => $data['outcome_notes'] ?? null,
                'reviewed_at' => now(),
            ]);

            if (!empty($data['assumption_evaluations'])) {
                foreach ($data['assumption_evaluations'] as $evaluation) {
                    $review->assumptionEvaluations()->create([
                        'assumption_id' => $evaluation['assumption_id'],
                        'was_correct' => $evaluation['was_correct'],
                    ]);
                }
            }

            // State transition: awaiting_review → reviewed
            $decision->update(['status' => 'reviewed']);

            return $review->load('assumptionEvaluations');
        });
    }
}