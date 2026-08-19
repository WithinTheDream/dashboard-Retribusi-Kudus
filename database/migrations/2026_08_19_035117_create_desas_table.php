<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kec_id')->constrained('kecamatans')->onDelete('restrict');
            $table->string('desa');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('desas');
    }
};
