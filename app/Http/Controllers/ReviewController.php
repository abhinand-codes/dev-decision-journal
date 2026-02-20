<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Decision;
use App\Services\CalibrationService;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService,
        protected CalibrationService $calibrationService
    ) {
    }

    public function store(StoreReviewRequest $request, Decision $decision): JsonResponse
    {
        $this->authorize('update', $decision);

        $review = $this->reviewService->submitReview(
            $decision,
            $request->validated()
        );

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }

    public function calibration(): JsonResponse
    {
        $data = $this->calibrationService->calculate(auth()->id());

        return response()->json($data);
    }
}