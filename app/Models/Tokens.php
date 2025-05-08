<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tokens extends Model
{
  
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    // Relasi ke User (Many to One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
