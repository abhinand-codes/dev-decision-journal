<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // SQLite (used in tests) does not support MODIFY COLUMN — skip safely.
        // SQLite ignores column type constraints, so the 'reviewed' value still works in tests.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE decisions MODIFY COLUMN status ENUM('pending','due','overdue','reviewed') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("UPDATE decisions SET status = 'pending' WHERE status = 'reviewed'");
            DB::statement("ALTER TABLE decisions MODIFY COLUMN status ENUM('pending','due','overdue') NOT NULL DEFAULT 'pending'");
        }
    }
};
