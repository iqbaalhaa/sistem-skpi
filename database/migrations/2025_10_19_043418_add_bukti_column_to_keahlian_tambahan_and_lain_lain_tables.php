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
        // Add bukti column to keahlian_tambahan table
        Schema::table('keahlian_tambahan', function (Blueprint $table) {
            $table->string('bukti')->nullable()->after('nomor_sertifikat');
        });

        // Add bukti column to lain_lain table
        Schema::table('lain_lain', function (Blueprint $table) {
            $table->string('bukti')->nullable()->after('nomor_sertifikat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove bukti column from keahlian_tambahan table
        Schema::table('keahlian_tambahan', function (Blueprint $table) {
            $table->dropColumn('bukti');
        });

        // Remove bukti column from lain_lain table
        Schema::table('lain_lain', function (Blueprint $table) {
            $table->dropColumn('bukti');
        });
    }
};
