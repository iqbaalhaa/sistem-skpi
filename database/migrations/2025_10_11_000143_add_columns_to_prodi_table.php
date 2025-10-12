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
        Schema::table('prodi', function (Blueprint $table) {
            $table->string('jenjang_pendidikan', 50)->nullable()->after('nama_prodi');
            $table->string('gelar', 100)->nullable()->after('jenjang_pendidikan');
            $table->string('akreditasi', 5)->nullable()->after('gelar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropColumn(['jenjang_pendidikan', 'gelar', 'akreditasi']);
        });
    }
};
