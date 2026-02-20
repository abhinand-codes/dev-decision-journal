<?php

namespace Database\Factories;

use App\Models\Decision;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Decision>
 */
class DecisionFactory extends Factory
{
    protected $model = Decision::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(6),
            'context' => fake()->paragraph(),
            'chosen_option' => fake()->words(3, true),
            'confidence_score' => fake()->numberBetween(0, 100),
            'review_date' => fake()->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
            'status' => 'pending',
        ];
    }

    /**
     * Set the decision as due for review (review_date in the past, still pending).
     */
    public function dueForReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'review_date' => today()->subDay()->toDateString(),
            'status' => 'pending',
        ]);
    }

    /**
     * Set the decision as already awaiting review.
     */
    public function awaitingReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'awaiting_review',
        ]);
    }

    /**
     * Set the decision as reviewed.
     */
    public function reviewed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'reviewed',
        ]);
    }
}
