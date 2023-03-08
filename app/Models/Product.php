<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'ycode_id',
        'name',
        'color',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'float',
    ];
}
