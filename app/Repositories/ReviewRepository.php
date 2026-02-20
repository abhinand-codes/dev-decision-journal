<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function create(array $data): Review
    {
        return Review::create($data);
    }

    public function getReviewedByUser(int $userId): Collection
    {
        return Review::with('decision')
            ->whereHas('decision', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }
}
