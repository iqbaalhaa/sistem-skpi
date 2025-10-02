<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengalamanMagang extends Model
{
    use HasFactory;
    protected $table = 'pengalaman_magang';
    protected $fillable = [
        'user_id',
        'keterangan_indonesia',
        'keterangan_inggris',
        'lembaga',
        'tahun',
        'bukti',
        'catatan',
        'verifikasi',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
