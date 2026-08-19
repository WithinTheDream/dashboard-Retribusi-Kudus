<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_retribusi_id')->constrained('jenis_retribusis')->onDelete('cascade');
            $table->decimal('nominal', 12, 2);
            $table->string('satuan');
            $table->year('periode');
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('tarifs');
    }
};
