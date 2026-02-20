<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDecisionRequest;
use App\Http\Requests\UpdateDecisionRequest;
use App\Models\Decision;
use App\Repositories\Contracts\DecisionRepositoryInterface;
use App\Services\DecisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DecisionController extends Controller
{
    public function __construct(
        protected DecisionRepositoryInterface $decisionRepository,
        protected DecisionService $decisionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'status',
            'confidence_min',
            'confidence_max',
            'tag_ids',
            'search'
        ]);

        $decisions = $this->decisionRepository->paginateByUser(
            1, // temporary user id
            $filters
        );

        return response()->json($decisions);
    }

    public function store(StoreDecisionRequest $request): JsonResponse
    {
        $decision = $this->decisionService->create(
            $request->validated(),
            1 // temporary user id
        );

        return response()->json($decision, 201);
    }

    public function show(Decision $decision): JsonResponse
    {
        return response()->json(
            $decision->load(['options', 'assumptions', 'tags', 'reviews'])
        );
    }

    public function update(UpdateDecisionRequest $request, Decision $decision): JsonResponse
    {
        $decision = $this->decisionService->update(
            $decision,
            $request->validated()
        );

        return response()->json($decision);
    }

    public function destroy(Decision $decision): JsonResponse
    {
        $this->decisionRepository->delete($decision);

        return response()->json(['message' => 'Deleted successfully']);
    }
}