<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request){
        $param = $request->adminlteSearch;

        $param = str_replace('-', '', $param);

        //商品番号
        $products = Product::select('product_code')->where('user_id', Auth::id())->get();
        $item_no = [];
        foreach ($products as $product) {
            $no = $product->product_code;
            array_push($item_no, $no);
        }
        //OrderDetailのitemNumberを管理している商品番号だけに絞り込む(narrow絞り込む)
        $narrow_datas = OrderDetail::select('orderNumber')->where('user_id', Auth::id())->whereIn('order_details.itemNumber', $item_no )->get();
        $results =Order::whereIn('orderNumber', $narrow_datas)->where(DB::raw('CONCAT(Order_familyName, Order_firstName)'), 'like', '%'.$param.'%')->orwhere('orderNumber','like', '%'.$param.'%')->orwhere('orderDate','like', '%'.$param.'%')->orwhere('Sender_prefecture','like', '%'.$param.'%')->orwhere('Sender_city','like', '%'.$param.'%')->orwhere('order_tel','like', '%'.$param.'%')->orwhere('sender_tel','like', '%'.$param.'%')->orderBy('orderDate','asc')->get();
        return view('search.index',compact('results'));
    }
}



