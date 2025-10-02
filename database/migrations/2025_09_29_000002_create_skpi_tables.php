<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Penghargaan/Prestasi
        Schema::create('penghargaan_prestasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('keterangan_indonesia');
            $table->string('keterangan_inggris');
            $table->string('jenis_organisasi', 100);
            $table->year('tahun');
            $table->string('bukti')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('verifikasi')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Pengalaman Berorganisasi
        Schema::create('pengalaman_organisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('organisasi');
            $table->year('tahun_awal');
            $table->year('tahun_akhir');
            $table->string('bukti')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('verifikasi')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Kompetensi Berkomunikasi Bahasa Internasional
        Schema::create('kompetensi_bahasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_kompetensi');
            $table->string('skor_kompetensi');
            $table->year('tahun');
            $table->string('bukti')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('verifikasi')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Pengalaman Magang
        Schema::create('pengalaman_magang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('keterangan_indonesia');
            $table->string('keterangan_inggris');
            $table->string('lembaga');
            $table->year('tahun');
            $table->string('bukti')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('verifikasi')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 5. Kompetensi Keagamaan
        Schema::create('kompetensi_keagamaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('keterangan_indonesia');
            $table->string('keterangan_inggris');
            $table->year('tahun');
            $table->string('bukti')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('verifikasi')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kompetensi_keagamaan');
        Schema::dropIfExists('pengalaman_magang');
        Schema::dropIfExists('kompetensi_bahasa');
        Schema::dropIfExists('pengalaman_organisasi');
        Schema::dropIfExists('penghargaan_prestasi');
    }
};
