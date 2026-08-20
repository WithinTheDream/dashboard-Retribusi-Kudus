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
        Schema::create('setorans', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_setoran')->unique();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // Petugas penyetor

        $table->date('tanggal_setor');
        $table->decimal('total_setoran', 12, 2);

        $table->enum('status_setoran', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
        $table->foreignId('bendahara_id')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('waktu_verifikasi')->nullable();
        $table->text('catatan')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
