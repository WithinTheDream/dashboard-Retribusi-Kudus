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
        Schema::create('tagihans', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_tagihan')->unique();
        $table->foreignId('wajib_retribusi_id')->constrained('wajib_retribusis')->restrictOnDelete();

        $table->integer('bulan');
        $table->integer('tahun');
        $table->decimal('nominal', 12, 2);

        $table->enum('status', ['belum_bayar', 'lunas', 'dibatalkan'])->default('belum_bayar');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
