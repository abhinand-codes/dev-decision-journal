<?php

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function create(array $data): Review;

    public function getReviewedByUser(int $userId): Collection;
}