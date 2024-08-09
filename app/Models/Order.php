<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_address', 'user_name', 'user_phone'];

    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getTotalAttribute()
    {
        return $this->orderDetails->sum('total');
    }
}
