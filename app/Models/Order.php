<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    // モデルに関連付けるテーブル
    protected $table = 'orders';
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
    public function orderdetail()
    {
        return $this->belongsTo(OrderDetail::class,'orderNumber');
    }

}
