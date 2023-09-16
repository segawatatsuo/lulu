<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ec_mall',
        'order_id',
        'order_date',
        'sku',
        'product_code',
        'product_name',
        'quantity',
        'purchaser_name',
        'total',
        'tax',
        'shipping_fee',
        'commission',
        'other_expenses',
        'use_point',
        'payment_total',
        'name01',
        'postal_code',
        'addr01',
        'delivery_method',
        'payment_method',
        'tracking_number',
        'note',
        'processing_status',
        'cancel_flag',
        'pause_flag',
        'sort_no',

    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
