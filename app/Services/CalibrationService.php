<?php

namespace App\Services;

use App\Repositories\Contracts\ReviewRepositoryInterface;

class CalibrationService
{
    public function __construct(
        protected ReviewRepositoryInterface $reviewRepository
    ) {}

    public function calculate(int $userId): array
    {
        $reviews = $this->reviewRepository->getReviewedByUser($userId);

        if ($reviews->count() < 10) {
            return [
                'message' => 'Not enough reviewed decisions for reliable calibration.',
                'total'   => $reviews->count(),
            ];
        }

        $data = [];

        foreach ($reviews as $review) {
            $confidence = $review->decision->confidence_score / 100;
            $outcome    = $review->was_successful ? 1 : 0;

            $data[] = [
                'confidence' => $confidence,
                'outcome'    => $outcome,
                'error'      => pow($confidence - $outcome, 2),
            ];
        }

        $overallAccuracy = collect($data)->avg('outcome');
        $averageConfidence = collect($data)->avg('confidence');
        $brierScore = collect($data)->avg('error');

        return [
            'total_reviews'      => count($data),
            'overall_accuracy'   => round($overallAccuracy * 100, 2),
            'average_confidence' => round($averageConfidence * 100, 2),
            'bias'               => round(($averageConfidence - $overallAccuracy) * 100, 2),
            'brier_score'        => round($brierScore, 4),
        ];
    }
}
