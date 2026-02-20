<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDecisionRequest;
use App\Http\Requests\UpdateDecisionRequest;
use App\Http\Resources\DecisionResource;
use App\Models\Decision;
use App\Repositories\Contracts\DecisionRepositoryInterface;
use App\Services\DecisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DecisionController extends Controller
{
    public function __construct(
        protected DecisionRepositoryInterface $decisionRepository,
        protected DecisionService $decisionService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only([
            'status',
            'confidence_min',
            'confidence_max',
            'tag_ids',
            'search',
        ]);

        $decisions = $this->decisionRepository->paginateByUser(
            $request->user()->id,
            $filters
        );

        return DecisionResource::collection($decisions);
    }

    public function store(StoreDecisionRequest $request): JsonResponse
    {
        $decision = $this->decisionService->create(
            $request->validated(),
            $request->user()->id
        );

        return (new DecisionResource($decision))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Decision $decision): DecisionResource
    {
        $this->authorize('view', $decision);

        return new DecisionResource(
            $decision->load(['options', 'assumptions', 'tags', 'latestReview.assumptionEvaluations'])
        );
    }

    public function update(UpdateDecisionRequest $request, Decision $decision): DecisionResource
    {
        $this->authorize('update', $decision);

        $decision = $this->decisionService->update(
            $decision,
            $request->validated()
        );

        return new DecisionResource($decision);
    }

    public function destroy(Decision $decision): JsonResponse
    {
        $this->authorize('delete', $decision);

        $this->decisionRepository->delete($decision);

        return response()->json(['message' => 'Decision deleted successfully.']);
    }
}