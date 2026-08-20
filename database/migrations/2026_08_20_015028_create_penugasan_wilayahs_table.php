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
        Schema::create('penugasan_wilayahs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // Petugas lapangan
        $table->foreignId('kecamatan_id')->constrained('kecamatans')->restrictOnDelete();
        $table->foreignId('desa_id')->constrained('desas')->restrictOnDelete();
        $table->string('rw', 3)->nullable(); // Null jika bertugas se-desa penuh
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_wilayahs');
    }
};
