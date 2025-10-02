<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkpi extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_skpi';

    protected $fillable = [
        'user_id',
        'status',
        'catatan_admin',
        'tanggal_pengajuan',
        'tanggal_verifikasi_prodi',
        'tanggal_verifikasi_fakultas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
