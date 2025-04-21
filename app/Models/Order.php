<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bar_id',
        'total',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bar()
    {
        return $this->belongsTo(User::class, 'bar_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
