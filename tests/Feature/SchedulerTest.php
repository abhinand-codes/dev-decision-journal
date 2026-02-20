<?php

namespace Tests\Feature;

use App\Models\Decision;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A pending decision whose review_date is in the past must be transitioned
     * to 'awaiting_review' when the command runs.
     */
    public function test_pending_decision_with_past_review_date_becomes_awaiting_review(): void
    {
        $user = User::factory()->create();

        $decision = Decision::factory()->create([
            'user_id' => $user->id,
            'review_date' => today()->subDay()->toDateString(), // yesterday
            'status' => 'pending',
        ]);

        $this->artisan('decisions:mark-awaiting-review')
            ->assertSuccessful();

        $this->assertDatabaseHas('decisions', [
            'id' => $decision->id,
            'status' => 'awaiting_review',
        ]);
    }

    /**
     * A pending decision whose review_date is today must also be transitioned.
     */
    public function test_pending_decision_due_today_becomes_awaiting_review(): void
    {
        $user = User::factory()->create();

        $decision = Decision::factory()->create([
            'user_id' => $user->id,
            'review_date' => today()->toDateString(),
            'status' => 'pending',
        ]);

        $this->artisan('decisions:mark-awaiting-review')
            ->assertSuccessful();

        $this->assertDatabaseHas('decisions', [
            'id' => $decision->id,
            'status' => 'awaiting_review',
        ]);
    }

    /**
     * A pending decision whose review_date is in the future must NOT be touched.
     */
    public function test_pending_decision_with_future_review_date_stays_pending(): void
    {
        $user = User::factory()->create();

        $decision = Decision::factory()->create([
            'user_id' => $user->id,
            'review_date' => today()->addDay()->toDateString(), // tomorrow
            'status' => 'pending',
        ]);

        $this->artisan('decisions:mark-awaiting-review')
            ->assertSuccessful();

        $this->assertDatabaseHas('decisions', [
            'id' => $decision->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Decisions that are already in a terminal-like state (reviewed, awaiting_review)
     * must not be modified even if their review_date has passed.
     */
    public function test_already_reviewed_decision_is_not_modified(): void
    {
        $user = User::factory()->create();

        $reviewed = Decision::factory()->create([
            'user_id' => $user->id,
            'review_date' => today()->subWeek()->toDateString(),
            'status' => 'reviewed',
        ]);

        $this->artisan('decisions:mark-awaiting-review')
            ->assertSuccessful();

        $this->assertDatabaseHas('decisions', [
            'id' => $reviewed->id,
            'status' => 'reviewed',
        ]);
    }

    /**
     * The command output must report the exact count of updated records.
     */
    public function test_command_prints_number_of_updated_records(): void
    {
        $user = User::factory()->create();

        Decision::factory()->count(3)->create([
            'user_id' => $user->id,
            'review_date' => today()->subDay()->toDateString(),
            'status' => 'pending',
        ]);

        // One that should NOT be updated
        Decision::factory()->create([
            'user_id' => $user->id,
            'review_date' => today()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        $this->artisan('decisions:mark-awaiting-review')
            ->expectsOutput('Marked 3 decision(s) as awaiting_review.')
            ->assertSuccessful();
    }
}
