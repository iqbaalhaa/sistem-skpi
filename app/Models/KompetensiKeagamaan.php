<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiKeagamaan extends Model
{
    use HasFactory;
    protected $table = 'kompetensi_keagamaan';
    protected $fillable = [
        'user_id',
        'keterangan_indonesia',
        'keterangan_inggris',
        'tahun',
        'bukti',
        'catatan',
        'verifikasi',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
