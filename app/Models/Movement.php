<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bar_id',
        'amount',
    ];

    // Relación con el usuario que recarga
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el bar donde se recarga
    public function bar()
    {
        return $this->belongsTo(User::class, 'bar_id');
    }
}
