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
    Schema::create('decision_tag', function (Blueprint $table) {
        $table->id();

        $table->foreignId('decision_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('tag_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->timestamps();

        $table->unique(['decision_id', 'tag_id']);
        $table->index('decision_id');
        $table->index('tag_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_tag');
    }
};
