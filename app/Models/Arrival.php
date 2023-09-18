<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Arrival extends Model
{
    use HasFactory;
    // モデルに関連付けるテーブル
    protected $table = 'arrivals';
    protected $fillable = [
        'arrival',
        'item_id',
        'user_id',
    ];

    //リレーション
    public function items()
    {
        return $this->belongsTo(Item::class);
    }

    //ログインユーザーのIDをuser_idに保存
    protected static function boot()
    {
        parent::boot();
        // 保存時user_idをログインユーザーに設定
        self::saving(function ($arrival) {
            $arrival->user_id = \Auth::id();
        });
    }

    public function InsertItem($request, $queryParameters)
    {
        // リクエストデータを基にItemsテーブルに登録する
        //dd($queryParameters);

        $arrival = new Arrival();
        $arrival->fill(
            [
                'item_id' => $queryParameters,
                'arrival' => $request->arrival,
            ]
        );

        return $arrival->save();

        /*
        return $this->create([
            'arrival' => $request->arrivals,
        ]);
        */
    }
}
