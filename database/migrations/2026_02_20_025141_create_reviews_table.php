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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();

        $table->foreignId('decision_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->boolean('was_successful'); // binary outcome for calibration

        $table->text('outcome_notes')->nullable();

        $table->timestamp('reviewed_at');

        $table->timestamps();

        $table->index('decision_id');
        $table->index('reviewed_at');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
