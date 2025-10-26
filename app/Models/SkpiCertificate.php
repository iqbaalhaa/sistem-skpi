<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkpiCertificate extends Model
{
    use HasFactory;

    protected $table = 'skpi_certificates';

    protected $fillable = [
        'user_id',
        'file_path',
        'generated_at',
        'nomor_surat',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
