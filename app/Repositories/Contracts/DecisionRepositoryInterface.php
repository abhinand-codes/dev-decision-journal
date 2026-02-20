<?php

namespace App\Repositories\Contracts;

use App\Models\Decision;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DecisionRepositoryInterface
{
    public function paginateByUser(int $userId, array $filters = []): LengthAwarePaginator;

    public function findById(int $id): ?Decision;

    public function create(array $data): Decision;

    public function update(Decision $decision, array $data): Decision;

    public function delete(Decision $decision): void;

    public function getDueDecisions(): Collection;
}