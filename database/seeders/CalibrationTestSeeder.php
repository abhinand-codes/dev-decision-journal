<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Decision;
use App\Models\Review;
use Carbon\Carbon;

class CalibrationTestSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {

            $confidence = rand(50, 95);

            $decision = Decision::create([
                'user_id' => 1,
                'title' => "Seed Decision {$i}",
                'context' => "Calibration test",
                'chosen_option' => 'Option A',
                'confidence_score' => $confidence,
                'review_date' => Carbon::now()->subDays(10),
                'status' => 'reviewed',
            ]);

            // Intentionally introduce overconfidence pattern
            $wasSuccessful = $confidence >= 80
                ? rand(0, 100) < 60
                : rand(0, 100) < 80;

            Review::create([
                'decision_id' => $decision->id,
                'was_successful' => $wasSuccessful,
                'outcome_notes' => 'Seed outcome',
                'reviewed_at' => now(),
            ]);
        }
    }
}