<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordernum',
        'userid',
        'status',
        'grandtotal',
        'itemcount',
        'ispaid',
        'paymentmethod',
        'paymentid',
        'address'
    ];

    protected $casts = [
        'address' => 'array',
    ];
}
