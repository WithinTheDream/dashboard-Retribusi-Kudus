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
        Schema::create('setoran_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('setoran_id')->constrained('setorans')->cascadeOnDelete();

        // Relasi 1-to-1 dengan pembayaran (Satu pembayaran hanya bisa masuk ke 1 setoran)
        $table->foreignId('pembayaran_id')->unique()->constrained('pembayarans')->restrictOnDelete();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_details');
    }
};
