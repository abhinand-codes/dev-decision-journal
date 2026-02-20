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
    Schema::create('review_assumptions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('review_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('assumption_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->boolean('was_correct');

        $table->timestamps();

        $table->unique(['review_id', 'assumption_id']);
        $table->index('review_id');
        $table->index('assumption_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_assumptions');
    }
};
