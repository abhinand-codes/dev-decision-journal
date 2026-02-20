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
    Schema::create('decision_options', function (Blueprint $table) {
        $table->id();

        $table->foreignId('decision_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->string('label');
        $table->text('pros')->nullable();
        $table->text('cons')->nullable();

        $table->timestamps();

        $table->index('decision_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_options');
    }
};
