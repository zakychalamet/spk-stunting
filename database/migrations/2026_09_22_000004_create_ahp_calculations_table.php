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
        Schema::create('ahp_calculations', function (Blueprint $table) {
            $table->id();
            $table->decimal('lambda_max', 8, 4)->default(0);
            $table->decimal('ci', 8, 4)->default(0);
            $table->decimal('cr', 8, 4)->default(0);
            $table->boolean('is_consistent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_calculations');
    }
};
