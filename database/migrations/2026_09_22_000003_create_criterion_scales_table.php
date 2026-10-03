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
        Schema::create('criterion_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')->constrained('criteria')->cascadeOnDelete();
            $table->string('parameter', 100);
            $table->integer('score'); // 1, 2, 3, 4
            $table->string('label', 150); // e.g. Normal, Anemia Ringan, Anemia Sedang, Anemia Berat
            $table->string('category', 50)->default('Normal'); // Normal, Rendah, Sedang, Tinggi
            $table->decimal('min_value', 8, 2)->nullable();
            $table->decimal('max_value', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterion_scales');
    }
};
