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
        Schema::create('ahp_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ahp_calculation_id')->constrained('ahp_calculations')->cascadeOnDelete();
            $table->foreignId('criterion_id')->constrained('criteria')->cascadeOnDelete();
            $table->decimal('eigen_vector', 8, 4);
            $table->decimal('weight_percentage', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_weights');
    }
};
