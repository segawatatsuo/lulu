<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SearchOrderTemp;
use App\Models\Order;
use App\Models\Item;

use App\Services\homeService; //サービス

class HomeController extends Controller
{

    private $home;

    public function __construct()
    {
        $this->middleware('auth');

        $this->home = new homeService();
    }

    public function index()
    {
        /////////////////////////////
        //      データ取得
        /////////////////////////////
        //新しい楽天の注文データ取得
        //$this->home->rakuten_search_order();
        //楽天の既存データ更新
        $this->home->rakuten_update();

        /////////////////////////////
        //      トップページ
        /////////////////////////////
        //新規受付数
        $count_new = $this->home->count_new();
        //出荷待ち数
        $count_orderProgress = $this->home->count_orderProgress();
        //出荷済み数
        $count_complete = $this->home->count_complete();
        //登録商品数
        $items_count = $this->home->items_count();
        //受注金額requestPrice
        $sum_requestPrice = $this->home->sum_requestPrice();
        //受注件数
        $count_order = $this->home->count_order();
        //売れ筋上位3件だけ
        $top3  = $this->home->top3();

        return view('top.index', compact('count_new', 'count_orderProgress', 'count_complete', 'items_count', 'sum_requestPrice', 'count_order', 'top3'));
    }
}
