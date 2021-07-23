<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{

    protected $table = "useraddress";

    protected $fillable =[
        'userid',
        'address'
    ];

    protected $casts =[
        'address' => 'array',
    ];

    use HasFactory;
}
