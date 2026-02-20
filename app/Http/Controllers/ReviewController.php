<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Decision;
use App\Services\ReviewService;
use App\Services\CalibrationService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService,
        protected CalibrationService $calibrationService
    ) {}

    public function store(StoreReviewRequest $request, Decision $decision): JsonResponse
    {
        $review = $this->reviewService->submitReview(
            $decision,
            $request->validated()
        );

        return response()->json($review, 201);
    }

    public function calibration(): JsonResponse
    {
        $data = $this->calibrationService->calculate(1); // temporary user id

        return response()->json($data);
    }
}