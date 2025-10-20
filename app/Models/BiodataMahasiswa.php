<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'biodata_mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'prodi_id',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'tahun_masuk',
        'foto',
        'tanggal_lulus',
        'nomor_ijazah',
        'ipk',
        'judul_skripsi',
        'lama_studi'
    ];

    public function prodi()
    {
        return $this->belongsTo(\App\Models\Prodi::class, 'prodi_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
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

    public function keahlianTambahan() {
        return $this->hasMany(KeahlianTambahan::class, 'user_id', 'user_id');
    }

    public function lainLain() {
        return $this->hasMany(LainLain::class, 'user_id', 'user_id');
    }

}
