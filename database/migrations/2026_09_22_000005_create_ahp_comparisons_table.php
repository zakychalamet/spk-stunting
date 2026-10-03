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
        Schema::create('ahp_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ahp_calculation_id')->constrained('ahp_calculations')->cascadeOnDelete();
            $table->foreignId('criterion1_id')->constrained('criteria')->cascadeOnDelete();
            $table->foreignId('criterion2_id')->constrained('criteria')->cascadeOnDelete();
            $table->decimal('value', 8, 4); // Nilai skala Saaty (e.g. 1..9, 0.3333, etc)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_comparisons');
    }
};
