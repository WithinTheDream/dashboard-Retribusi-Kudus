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
        Schema::create('pembayarans', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_pembayaran')->unique();
        $table->foreignId('tagihan_id')->constrained('tagihans')->restrictOnDelete();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // Petugas penerima uang

        $table->decimal('nominal_bayar', 12, 2);
        $table->string('metode_pembayaran')->default('tunai'); // tunai, qris
        $table->timestamp('waktu_bayar');

        $table->string('lat')->nullable();
        $table->string('long')->nullable();

        // Flagging untuk sinkronisasi offline dan setoran
        $table->boolean('status_sync')->default(false);
        $table->boolean('is_setor')->default(false);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
