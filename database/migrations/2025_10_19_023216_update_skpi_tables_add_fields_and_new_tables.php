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
        // Tambahkan kolom nomor_sertifikat di tabel-tabel lama
        $tables = [
            'pengalaman_magang',
            'penghargaan_prestasi',
            'pengalaman_organisasi',
            'kompetensi_bahasa',
            'kompetensi_keagamaan',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('nomor_sertifikat', 100)->nullable()->after('verifikasi');
                });
            }
        }

        // === Buat tabel keahlian_tambahan ===
        Schema::create('keahlian_tambahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_keahlian', 150);
            $table->string('lembaga', 150)->nullable();
            $table->string('nomor_sertifikat', 100)->nullable();
            $table->year('tahun')->nullable();
            $table->tinyInteger('verifikasi')->default(0)->comment('0 = belum, 1 = diterima, 2 = ditolak');
            $table->timestamps();
        });

        // === Buat tabel lain_lain ===
        Schema::create('lain_lain', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_kegiatan', 150);
            $table->string('penyelenggara', 150)->nullable();
            $table->string('nomor_sertifikat', 100)->nullable();
            $table->year('tahun')->nullable();
            $table->tinyInteger('verifikasi')->default(0)->comment('0 = belum, 1 = diterima, 2 = ditolak');
            $table->timestamps();
        });
    }

    /**
     * Undo migrasi.
     */
    public function down(): void
    {
        // Hapus kolom nomor_sertifikat dari tabel-tabel lama
        $tables = [
            'pengalaman_magang',
            'penghargaan_prestasi',
            'pengalaman_organisasi',
            'kompetensi_bahasa',
            'kompetensi_keagamaan',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'nomor_sertifikat')) {
                        $table->dropColumn('nomor_sertifikat');
                    }
                });
            }
        }

        // Hapus tabel baru
        Schema::dropIfExists('keahlian_tambahan');
        Schema::dropIfExists('lain_lain');
    }
};
