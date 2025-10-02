<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiBahasa extends Model
{
    use HasFactory;
    protected $table = 'kompetensi_bahasa';
    protected $fillable = [
        'user_id',
        'nama_kompetensi',
        'skor_kompetensi',
        'tahun',
        'bukti',
        'catatan',
        'verifikasi',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
