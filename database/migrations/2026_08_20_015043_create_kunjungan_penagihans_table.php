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
        Schema::create('kunjungan_penagihans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // Petugas yang berkunjung
        $table->foreignId('wajib_retribusi_id')->constrained('wajib_retribusis')->restrictOnDelete();

        $table->timestamp('waktu_kunjungan');
        $table->string('hasil_kunjungan'); // bayar, rumah_kosong, menolak, dll
        $table->text('catatan')->nullable();

        $table->string('lat')->nullable();
        $table->string('long')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_penagihans');
    }
};
