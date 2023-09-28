<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SearchOrderTemp;
use App\Models\Order;
use App\Models\Item;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //新規受付数
        $count_new = SearchOrderTemp::Where('user_id',Auth::id())->count();

        //出荷待ち数
        $count_orderProgress = Order::whereNull('endFlag')->where(function($query){
            $query->where('orderProgress',300)->Where('user_id',Auth::id());
        })->count();
        //出荷済み数
        $count_complete = Order::whereNotNull('dateOfShipment')->where(function($query){
            $query->whereNotNull('shippingDocumentNumber')->Where('user_id',Auth::id());
        })->count();
        //登録商品数
        $items_count = Item::where('user_id',Auth::id())->count();
        //受注金額requestPrice
        $sum_requestPrice = Order::where('user_id',Auth::id())->sum('requestPrice');
        //受注件数
        $count_order = Order::where('user_id',Auth::id())->count();
        //売れ筋上位3件だけ
        $top3 = DB::table('order_details')
        ->select('itemName')
        ->selectRaw('SUM(priceTaxIncl) AS total_amount')
        ->groupBy('itemName')
        ->orderBy('total_amount', 'DESC')->take(3)
        ->get();



        return view('top.index',compact('count_new','count_orderProgress','count_complete','items_count','sum_requestPrice','count_order','top3'));
    }
}
