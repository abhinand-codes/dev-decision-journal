<?php

namespace App\Repositories;

use App\Models\Decision;
use App\Repositories\Contracts\DecisionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DecisionRepository implements DecisionRepositoryInterface
{
    public function paginateByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Decision::query()
            ->where('user_id', $userId)
            ->with(['options', 'assumptions', 'tags', 'reviews']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['confidence_min'])) {
            $query->where('confidence_score', '>=', $filters['confidence_min']);
        }

        if (!empty($filters['confidence_max'])) {
            $query->where('confidence_score', '<=', $filters['confidence_max']);
        }

        if (!empty($filters['tag_ids'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tag_ids']);
            });
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('context', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(15);
    }

    public function findById(int $id): ?Decision
    {
        return Decision::with(['options', 'assumptions', 'tags', 'reviews'])
            ->find($id);
    }

    public function create(array $data): Decision
    {
        return Decision::create($data);
    }

    public function update(Decision $decision, array $data): Decision
    {
        $decision->update($data);
        return $decision;
    }

    public function delete(Decision $decision): void
    {
        $decision->delete();
    }

    public function getDueDecisions(): Collection
    {
        return Decision::where('status', 'pending')
            ->whereDate('review_date', '<=', now()->toDateString())
            ->get();
    }
}