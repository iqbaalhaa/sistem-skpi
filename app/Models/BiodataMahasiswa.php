<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'biodata_mahasiswa';

    protected $fillable = [
        'user_id', 'nim', 'nama', 'prodi_id', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp', 'foto'
    ];

    public function prodi()
    {
        return $this->belongsTo(\App\Models\Prodi::class, 'prodi_id');
    }

    public function prestasi() {
        return $this->hasMany(PenghargaanPrestasi::class, 'user_id', 'user_id');
    }

    public function organisasi() {
        return $this->hasMany(PengalamanOrganisasi::class, 'user_id', 'user_id');
    }

    public function magang() {
        return $this->hasMany(PengalamanMagang::class, 'user_id', 'user_id');
    }

    public function kompetensiBahasa() {
        return $this->hasMany(KompetensiBahasa::class, 'user_id', 'user_id');
    }

    public function kompetensiKeagamaan() {
        return $this->hasMany(KompetensiKeagamaan::class, 'user_id', 'user_id');
    }

}
