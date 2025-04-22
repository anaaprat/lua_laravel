<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_drink',
        'type',
        'description',
        'image_url',
        'price'
    ];

    public function barProducts()
    {
        return $this->hasMany(BarProduct::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
