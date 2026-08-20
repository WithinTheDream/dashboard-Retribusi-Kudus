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
        Schema::create('dokumen_pengajuans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pengajuan_id')->constrained('pengajuan_wajib_retribusis')->cascadeOnDelete();
        $table->string('jenis_dokumen'); // Misal: KTP, NIB, FOTO_LOKASI
        $table->string('file_path');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pengajuans');
    }
};
