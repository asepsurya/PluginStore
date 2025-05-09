<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'plugin',
        'price',
        'status',
        'snap_token', // tambahkan ini
    ];
}
