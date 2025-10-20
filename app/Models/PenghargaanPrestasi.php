<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenghargaanPrestasi extends Model
{
    use HasFactory;
    protected $table = 'penghargaan_prestasi';
    protected $fillable = [
        'user_id',
        'keterangan_indonesia',
        'keterangan_inggris',
        'jenis_organisasi',
        'tahun',
        'bukti',
        'catatan',
        'verifikasi',
        'nomor_sertifikat',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
