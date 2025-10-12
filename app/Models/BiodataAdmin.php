<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataAdmin extends Model
{
    use HasFactory;

    protected $table = 'biodata_admin';

    protected $fillable = [
        'user_id', 'nama', 'foto',
    ];

    
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
