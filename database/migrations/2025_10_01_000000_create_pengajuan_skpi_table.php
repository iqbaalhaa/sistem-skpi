<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
            Schema::create('pengajuan_skpi', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->enum('status', ['menunggu','diterima_prodi','ditolak_prodi','diterima_fakultas','ditolak_fakultas'])->default('menunggu');
                $table->text('catatan_admin')->nullable();
                $table->timestamp('tanggal_pengajuan')->nullable();
                $table->timestamp('tanggal_verifikasi_prodi')->nullable();
                $table->timestamp('tanggal_verifikasi_fakultas')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_skpi');
    }
};
