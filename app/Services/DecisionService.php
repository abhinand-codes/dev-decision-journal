<?php

namespace App\Services;

use App\Models\Decision;
use App\Repositories\Contracts\DecisionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DecisionService
{
    public function __construct(
        protected DecisionRepositoryInterface $decisionRepository
    ) {}

    public function create(array $data, int $userId): Decision
    {
        return DB::transaction(function () use ($data, $userId) {

            $data['user_id'] = $userId;
            $data['status'] = 'pending';

            $decision = $this->decisionRepository->create($data);

            if (!empty($data['options'])) {
                foreach ($data['options'] as $option) {
                    $decision->options()->create($option);
                }
            }

            if (!empty($data['assumptions'])) {
                foreach ($data['assumptions'] as $assumption) {
                    $decision->assumptions()->create([
                        'description' => $assumption['description']
                    ]);
                }
            }

            if (!empty($data['tag_ids'])) {
                $decision->tags()->sync($data['tag_ids']);
            }

            return $decision->load(['options', 'assumptions', 'tags']);
        });
    }

    public function update(Decision $decision, array $data): Decision
    {
        return DB::transaction(function () use ($decision, $data) {

            $this->decisionRepository->update($decision, $data);

            return $decision->refresh()->load(['options', 'assumptions', 'tags']);
        });
    }
}