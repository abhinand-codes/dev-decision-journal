<?php

namespace App\Console\Commands;

use App\Models\Decision;
use Illuminate\Console\Command;

class MarkDecisionsAwaitingReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decisions:mark-awaiting-review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark decisions as awaiting_review when their review_date is today or in the past and status is still pending.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $updated = Decision::where('status', 'pending')
            ->whereDate('review_date', '<=', today())
            ->update(['status' => 'awaiting_review']);

        $this->info("Marked {$updated} decision(s) as awaiting_review.");

        return self::SUCCESS;
    }
}
