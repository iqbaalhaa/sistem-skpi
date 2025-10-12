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
        Schema::table('pengajuan_skpi', function (Blueprint $table) {
            $table->enum('status', ['menunggu','diterima_prodi','ditolak_prodi','diterima_fakultas','ditolak_fakultas'])
                  ->default('menunggu')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_skpi', function (Blueprint $table) {
            $table->enum('status', ['menunggu','diterima_prodi','ditolak_prodi'])
                  ->default('menunggu')
                  ->change();
        });
    }
};
