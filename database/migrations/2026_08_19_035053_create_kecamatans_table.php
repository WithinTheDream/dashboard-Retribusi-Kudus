<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('kecamatan');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('kecamatans');
    }
};
