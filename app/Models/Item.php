<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    use HasFactory;

    // モデルに関連付けるテーブル
    protected $table = 'items';

    // テーブルに関連付ける主キー(これを使うとfind()がおかしくなる)
    //protected $primaryKey = 'user_id';

    protected $fillable = [
        'sku',
        'product_code',
        'product_name',
        'quantity',
        'price',
        'stock',
    ];

    //リレーション
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    //ログインユーザーのIDをuser_idに保存
    protected static function boot()
    {
        parent::boot();
        // 保存時user_idをログインユーザーに設定
        self::saving(function($item) {
            $item->user_id = \Auth::id();
        });
    }

    /**
     * itemテーブルの全件取得
     */
    public function allItems()
    {
        return $this->all();
    }

    public function InsertItem($request)
    {
        // リクエストデータを基にItemsテーブルに登録する
        return $this->create([
            'sku' => $request->sku,
            'product_name' => $request->product_name,
            'stock' => $request->stock,
            'price' => $request->price,
        ]);
    }





}
