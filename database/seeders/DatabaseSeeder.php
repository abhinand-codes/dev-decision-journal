<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Decision;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── User ──────────────────────────────────────────────────────────────
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Tags ──────────────────────────────────────────────────────────────
        $tags = collect(['architecture', 'database', 'process'])->map(
            fn($name) => \App\Models\Tag::create(['name' => $name])
        );

        // ── Decision id=1 (with assumptions so review endpoint works) ─────────
        $decision = Decision::create([
            'user_id' => $user->id,
            'title' => 'Choose Database Engine',
            'context' => 'Need to choose a DB engine for scaling our product.',
            'chosen_option' => 'PostgreSQL',
            'confidence_score' => 80,
            'review_date' => now()->addMonths(4)->toDateString(),
            'status' => 'pending',
        ]);

        // Attach tags
        $decision->tags()->sync($tags->pluck('id'));

        // Create assumptions (ids will be 1 and 2)
        $decision->assumptions()->createMany([
            ['description' => 'PostgreSQL has better JSON support than MySQL.'],
            ['description' => 'Our team has sufficient PostgreSQL experience.'],
        ]);

        // Seed 20 reviewed decisions with varied outcomes for calibration testing
        $this->call(CalibrationTestSeeder::class);
    }
}
