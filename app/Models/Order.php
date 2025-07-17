<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_email',
        'total_amount',
        'status',
        'revolut_order_id',
    ];
}
