<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CalibrationTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalibrationTest extends TestCase
{
    use RefreshDatabase; // runs migrate:fresh before each test, gives us a clean SQLite schema

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_calibration_returns_valid_structure(): void
    {
        // CalibrationTestSeeder hardcodes user_id=1, so we must create the user first
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'test@example.com',
        ]);

        $this->seed(CalibrationTestSeeder::class);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/calibration');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_reviews',
                'overall_accuracy',
                'average_confidence',
                'overall_bias',
                'calibration_buckets',
            ]);
    }
}
