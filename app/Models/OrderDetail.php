<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    // モデルに関連付けるテーブル
    protected $table = 'order_details';
    //整数値ではない主キーなのでインクリメントさせない
    public $incrementing = false;
    //主キーは文字型
    protected $keyType = 'string';
    //主キーはorderNumber
    protected $primaryKey = 'orderNumber';
    protected $guarded = [
        'id',
    ];

    //リレーション
    public function order()
    {
        return $this->hasMany(Order::class,'orderNumber');
    }

}
