<?php

namespace Tests\Feature;

use App\Models\Decision;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecisionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_decision_resource_format_and_relationship_boundaries(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'Architecture']);

        /** @var Decision $decision */
        $decision = Decision::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Decision',
            'review_date' => '2026-02-25',
        ]);
        $decision->tags()->attach($tag);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/decisions/{$decision->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'context',
                    'chosen_option',
                    'confidence_score',
                    'review_date',
                    'status',
                    'created_at',
                    'tags',
                    'review', // Loaded in show()
                ]
            ])
            ->assertJsonPath('data.title', 'Test Decision')
            ->assertJsonPath('data.review_date', '2026-02-25');

        // Verify tags exist but HAVE NO PIVOT DATA
        $tagsData = $response->json('data.tags');
        $this->assertCount(1, $tagsData);
        $this->assertEquals('Architecture', $tagsData[0]['name']);
        $this->assertArrayNotHasKey('pivot', $tagsData[0], 'Pivot data leaked into API response!');

        // Verify whenLoaded works: review key is present because show() loads it
        $this->assertArrayHasKey('review', $response->json('data'));
    }

    public function test_when_loaded_prevents_unloaded_relationships_from_appearing(): void
    {
        $user = User::factory()->create();
        $decision = Decision::factory()->create(['user_id' => $user->id]);

        // Wrap the raw model in the resource WITHOUT loading review
        $resource = new \App\Http\Resources\DecisionResource($decision);
        $json = $resource->response()->getData(true);

        $this->assertArrayNotHasKey('review', $json['data'], 'Unloaded relationship leaked into JSON!');
        $this->assertArrayNotHasKey('tags', $json['data'], 'Unloaded relationship leaked into JSON!');
    }

    public function test_decision_index_returns_resource_collection(): void
    {
        $user = User::factory()->create();
        Decision::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/decisions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'status',
                        'review_date',
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_decision_deletion_returns_204_no_content(): void
    {
        $user = User::factory()->create();
        $decision = Decision::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/decisions/{$decision->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('decisions', ['id' => $decision->id]);
    }
}
