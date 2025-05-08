<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'plugin_name',
        'plugin_file',
        'logo',
        'description',
        'harga',
        'versi',
        'status',
    ];
}
