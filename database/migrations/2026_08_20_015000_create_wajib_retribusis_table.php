<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wajib_retribusis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();

            // Relasi ke pengajuan awal dan akun user (Wajib Ada)
            $table->foreignId('pengajuan_id')->unique()->constrained('pengajuan_wajib_retribusis')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

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
            $table->foreignId('jenis_retribusi_id')->constrained('jenis_retribusis')->restrictOnDelete();

            // Pengganti status_pengajuan (Hanya True jika aktif, False jika diblokir/pindah)
            $table->boolean('status_aktif')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wajib_retribusis');
    }
};
