<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchOrder extends Model
{
    use HasFactory;

    // モデルに関連付けるテーブル
    protected $table = 'search_orders';
    //整数値ではない主キーなのでインクリメントさせない
    public $incrementing = false;
    //主キーは文字型
    protected $keyType = 'string';
    // テーブルに関連付ける主キー
    protected $primaryKey = 'order_number';

    protected $fillable = [
        'order_number',
        'user_id',
    ];

    public function search_order_temp()
    {
        return $this->belongsTo(SearchOrderTemp::class,'order_number');
    }

}
