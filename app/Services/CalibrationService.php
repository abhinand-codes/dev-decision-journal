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
    $reviews = \App\Models\Review::with('decision')
        ->whereHas('decision', fn ($q) => $q->where('user_id', $userId))
        ->get();

    $total = $reviews->count();

    if ($total < 5) {
        return [
            'message' => 'Not enough reviewed decisions for reliable calibration.',
            'total' => $total
        ];
    }

    $correct = $reviews->where('was_successful', true)->count();
    $overallAccuracy = round(($correct / $total) * 100, 2);

    $buckets = [];

    foreach ($reviews as $review) {
        $confidence = $review->decision->confidence_score;
        $bucket = floor($confidence / 10) * 10;
        $buckets[$bucket][] = $review->was_successful ? 1 : 0;
    }

    $bucketData = [];

    foreach ($buckets as $confidence => $results) {
        $accuracy = array_sum($results) / count($results) * 100;

        $bucketData[] = [
            'confidence_bucket' => $confidence,
            'actual_accuracy' => round($accuracy, 2),
            'count' => count($results),
            'bias' => round($accuracy - $confidence, 2),
        ];
    }

    $averageConfidence = $reviews->avg(fn ($r) => $r->decision->confidence_score);
    $bias = $overallAccuracy - $averageConfidence;

    return [
        'total_reviews' => $total,
        'overall_accuracy' => $overallAccuracy,
        'average_confidence' => round($averageConfidence, 2),
        'overall_bias' => round($bias, 2),
        'calibration_buckets' => $bucketData,
        'interpretation' => $bias < 0 ? 'overconfident' : 'underconfident'
    ];
}
}
