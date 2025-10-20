<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // === Tabel biodata_mahasiswa ===
        Schema::table('biodata_mahasiswa', function (Blueprint $table) {
            $table->date('tanggal_lulus')->nullable()->after('tahun_masuk');
            $table->string('nomor_ijazah', 100)->nullable()->after('tanggal_lulus');
            $table->decimal('ipk', 3, 2)->nullable()->after('nomor_ijazah');
            $table->text('judul_skripsi')->nullable()->after('ipk');
            $table->string('lama_studi', 50)->nullable()->after('judul_skripsi');
        });

        // === Tabel biodata_admin ===
        Schema::table('biodata_admin', function (Blueprint $table) {
            $table->string('nama_kaprodi', 150)->nullable()->after('nama');
            $table->string('nama_dekan', 150)->nullable()->after('nama_kaprodi');
            $table->string('nip', 50)->nullable()->after('nama_dekan');
        });
    }

    /**
     * Undo migrasi.
     */
    public function down(): void
    {
        Schema::table('biodata_mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['tanggal_lulus', 'nomor_ijazah', 'ipk', 'judul_skripsi', 'lama_studi']);
        });

        Schema::table('biodata_admin', function (Blueprint $table) {
            $table->dropColumn(['nama_kaprodi', 'nama_dekan', 'nip']);
        });
    }
};
