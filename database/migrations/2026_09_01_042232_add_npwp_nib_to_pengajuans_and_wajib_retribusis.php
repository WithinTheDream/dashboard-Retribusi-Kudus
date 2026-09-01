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
        Schema::table('pengajuan_wajib_retribusis', function (Blueprint $table) {
            $table->string('npwp', 50)->nullable()->after('nama_usaha');
            $table->string('nib', 50)->nullable()->after('npwp');
        });

        Schema::table('wajib_retribusis', function (Blueprint $table) {
            $table->string('npwp', 50)->nullable()->after('nama_usaha');
            $table->string('nib', 50)->nullable()->after('npwp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_wajib_retribusis', function (Blueprint $table) {
            $table->dropColumn(['npwp', 'nib']);
        });

        Schema::table('wajib_retribusis', function (Blueprint $table) {
            $table->dropColumn(['npwp', 'nib']);
        });
    }
};
