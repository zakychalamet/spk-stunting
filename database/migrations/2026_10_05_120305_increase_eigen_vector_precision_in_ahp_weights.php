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
        Schema::table('ahp_weights', function (Blueprint $table) {
            $table->decimal('eigen_vector', 14, 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ahp_weights', function (Blueprint $table) {
            $table->decimal('eigen_vector', 8, 4)->change();
        });
    }
};
