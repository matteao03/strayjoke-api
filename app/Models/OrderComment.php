<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    protected $fillable = ['order_id', 'comment', 'rating'];

    //订单
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
