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
        Schema::create('pengajuan_wajib_retribusis', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_pengajuan')->unique();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
        $table->foreignId('jenis_retribusi_id')->constrained('jenis_retribusis')->restrictOnDelete();

        $table->string('nik', 16);
        $table->string('nama_lengkap');
        $table->string('nama_usaha')->nullable();

        $table->foreignId('kecamatan_id')->constrained('kecamatans')->restrictOnDelete();
        $table->foreignId('desa_id')->constrained('desas')->restrictOnDelete();
        $table->text('alamat');
        $table->string('rt', 3);
        $table->string('rw', 3);
        $table->string('lokasi_long')->nullable();
        $table->string('lat')->nullable();

        $table->string('no_hp');

        $table->enum('status_pengajuan', ['menunggu', 'perbaikan', 'survey', 'ditolak', 'disetujui'])->default('menunggu');
        $table->text('catatan_admin')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_wajib_retribusis');
    }
};
