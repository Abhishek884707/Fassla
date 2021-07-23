<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories_id',
        'sub_categories_id',
        'name',
        'mrp',
        'price',
        'quantity',
        'qty_value',
        'image',
        'short_desc',
        'description',
        'best_seller',
        'meta_title',
        'meta_desc',
        'meta_keyword',
        'status',
    ];

    protected $casts = [
        'image' => 'array',
        'price' => 'array',
    ];
    
}
