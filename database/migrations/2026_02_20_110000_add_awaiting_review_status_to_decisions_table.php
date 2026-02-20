<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Extend the decisions.status enum to include 'awaiting_review'.
     *
     * SQLite (used in tests) does not support MODIFY COLUMN and also does not
     * enforce enum constraints, so the value 'awaiting_review' already works
     * transparently in the test environment without any DDL change.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement(
                "ALTER TABLE decisions
                 MODIFY COLUMN status
                 ENUM('pending', 'awaiting_review', 'due', 'overdue', 'reviewed')
                 NOT NULL DEFAULT 'pending'"
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement(
                "UPDATE decisions SET status = 'pending' WHERE status = 'awaiting_review'"
            );
            DB::statement(
                "ALTER TABLE decisions
                 MODIFY COLUMN status
                 ENUM('pending', 'due', 'overdue', 'reviewed')
                 NOT NULL DEFAULT 'pending'"
            );
        }
    }
};
