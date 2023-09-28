<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchOrderTemp extends Model
{
    use HasFactory;

    // モデルに関連付けるテーブル
    protected $table = 'search_order_temps';
    //整数値ではない主キーなのでインクリメントさせない
    public $incrementing = false;
    //主キーは文字型
    protected $keyType = 'string';
    //主キーはorder_number
    protected $primaryKey = 'order_number';

    protected $fillable = [
        'order_number',
        'user_id',
    ];

    public function search_order()
    {
        return $this->hasMany(SearchOrder::class,'order_number');
    }
    
    public function order()
    {
        return $this->hasMany(Order::class,'orderNumber');
    }

}
