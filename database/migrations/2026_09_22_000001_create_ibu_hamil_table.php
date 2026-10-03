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
        Schema::create('ibu_hamil', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ibu_hamil', 50)->nullable()->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('nomor_telepon', 50)->nullable();
            $table->string('desa_kelurahan', 100)->nullable();
            $table->date('hpht');
            $table->date('hpl');
            $table->integer('usia_kehamilan_minggu')->default(0);
            $table->string('status_kehamilan', 50)->nullable(); // Trimester I, Trimester II, Trimester III
            $table->string('status_anemia', 50)->nullable(); // Tidak Anemia, Anemia Ringan, Anemia Sedang, Anemia Berat
            $table->decimal('kadar_hb', 4, 2)->nullable();
            $table->decimal('imt', 5, 2)->default(0);
            $table->decimal('lila', 5, 2)->default(0);
            $table->decimal('berat_badan_sebelum_hamil', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibu_hamil');
    }
};
