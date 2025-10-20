<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianTambahan extends Model
{
    use HasFactory;

    protected $table = 'keahlian_tambahan';

    protected $fillable = [
        'user_id',
        'nama_keahlian',
        'lembaga',
        'tahun',
        'nomor_sertifikat',
        'bukti',
        'verifikasi',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
