<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ycode_order_number',
        'ycode_id',
        'name',
        'customer_name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'country',
        'state',
        'zip',
        'total',
        'subtotal',
        'shipping',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
