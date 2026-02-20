<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('decisions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->string('title');
        $table->text('context');

        $table->string('chosen_option');
        $table->unsignedTinyInteger('confidence_score'); // 0–100

        $table->date('review_date');

        $table->enum('status', ['pending', 'due', 'overdue'])
              ->default('pending');

        $table->timestamps();

        // Indexes for performance
        $table->index(['user_id', 'status']);
        $table->index('review_date');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
