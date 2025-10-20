<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengalamanOrganisasi extends Model
{
    use HasFactory;
    protected $table = 'pengalaman_organisasi';
    protected $fillable = [
        'user_id',
        'organisasi',
        'tahun_awal',
        'tahun_akhir',
        'bukti',
        'catatan',
        'verifikasi',
        'nomor_sertifikat',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
