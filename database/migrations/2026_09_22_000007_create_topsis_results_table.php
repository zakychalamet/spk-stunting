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
        Schema::create('topsis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_hamil_id')->constrained('ibu_hamil')->cascadeOnDelete();
            $table->foreignId('ahp_calculation_id')->nullable()->constrained('ahp_calculations')->nullOnDelete();
            $table->decimal('score_anemia', 5, 2)->default(0);
            $table->decimal('score_imt', 5, 2)->default(0);
            $table->decimal('score_lila', 5, 2)->default(0);
            $table->decimal('score_usia', 5, 2)->default(0);
            $table->decimal('d_plus', 10, 6)->default(0);
            $table->decimal('d_minus', 10, 6)->default(0);
            $table->decimal('preference_score', 10, 6)->default(0);
            $table->integer('rank')->default(0);
            $table->string('priority', 50)->default('Rendah'); // Tinggi, Sedang, Rendah
            $table->text('main_risk_factors')->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topsis_results');
    }
};
