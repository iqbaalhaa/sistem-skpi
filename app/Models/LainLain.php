<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LainLain extends Model
{
    use HasFactory;

    protected $table = 'lain_lain';

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'penyelenggara',
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
