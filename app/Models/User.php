<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
    
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'prodi_id',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke pengajuan SKPI
    public function pengajuanSkpi()
    {
        return $this->hasMany(\App\Models\PengajuanSkpi::class, 'user_id');
    }

    // Relasi ke biodata mahasiswa
    public function biodataMahasiswa()
    {
        return $this->hasOne(\App\Models\BiodataMahasiswa::class, 'user_id');
    }

    // Relasi ke penghargaan/prestasi
    public function prestasi()
    {
        return $this->hasMany(\App\Models\PenghargaanPrestasi::class, 'user_id');
    }

    // Relasi ke pengalaman organisasi
    public function organisasi()
    {
        return $this->hasMany(\App\Models\PengalamanOrganisasi::class, 'user_id');
    }

    // Relasi ke pengalaman magang
    public function magang()
    {
        return $this->hasMany(\App\Models\PengalamanMagang::class, 'user_id');
    }

    // Relasi ke kompetensi bahasa
    public function kompetensiBahasa()
    {
        return $this->hasMany(\App\Models\KompetensiBahasa::class, 'user_id');
    }

    // Relasi ke kompetensi keagamaan
    public function kompetensiKeagamaan()
    {
        return $this->hasMany(\App\Models\KompetensiKeagamaan::class, 'user_id');
    }

    // Relasi ke prodi
    public function prodi()
    {
        return $this->belongsTo(\App\Models\Prodi::class, 'prodi_id');
    }
    
    public function biodataAdmin()
    {
        return $this->hasOne(BiodataAdmin::class, 'user_id');
    }

    public function keahlianTambahan()
    {
        return $this->hasMany(KeahlianTambahan::class, 'user_id');
    }

    public function lainLain()
    {
        return $this->hasMany(LainLain::class, 'user_id');
    }

}

