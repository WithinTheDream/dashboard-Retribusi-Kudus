<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('wajib_retribusis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('nama_usaha')->nullable();
            $table->text('alamat');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('restrict');
            $table->foreignId('desa_id')->constrained('desas')->onDelete('restrict');
            $table->string('lokasi_long')->nullable();
            $table->string('lat')->nullable();
            $table->string('no_hp');
            $table->foreignId('jenis_retribusi_id')->constrained('jenis_retribusis')->onDelete('restrict');
            $table->string('nib')->nullable();
            $table->string('dokumen_nib')->nullable();
            $table->string('npwp')->nullable();
            $table->string('dokumen_npwp')->nullable();
            $table->enum('status_pengajuan', ['menunggu', 'perbaikan', 'ditolak', 'lolos'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('wajib_retribusis');
    }
};
