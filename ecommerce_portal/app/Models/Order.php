<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'user_id',
        'order_no',
        'status',
        'payment_mode',
        'total'
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

}
