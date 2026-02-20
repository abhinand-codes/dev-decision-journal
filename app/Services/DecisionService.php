<?php

namespace App\Services;

use App\Models\Decision;
use App\Repositories\Contracts\DecisionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DecisionService
{
    public function __construct(
        protected DecisionRepositoryInterface $decisionRepository
    ) {
    }

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
                        'description' => $assumption['description'],
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

            // Update scalar fields (title, context, etc.)
            $this->decisionRepository->update($decision, $data);

            // FIX: sync tags if provided
            if (array_key_exists('tag_ids', $data)) {
                $decision->tags()->sync($data['tag_ids'] ?? []);
            }

            // FIX: sync options if provided (delete and recreate)
            if (array_key_exists('options', $data)) {
                $decision->options()->delete();
                foreach ($data['options'] ?? [] as $option) {
                    $decision->options()->create($option);
                }
            }

            // FIX: sync assumptions if provided (delete and recreate)
            if (array_key_exists('assumptions', $data)) {
                $decision->assumptions()->delete();
                foreach ($data['assumptions'] ?? [] as $assumption) {
                    $decision->assumptions()->create([
                        'description' => $assumption['description'],
                    ]);
                }
            }

            return $decision->refresh()->load(['options', 'assumptions', 'tags', 'latestReview']);
        });
    }
}