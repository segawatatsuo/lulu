<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\SearchOrderTemp;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;

use Ramsey\Uuid\Type\Integer;

use App\Http\Requests\StoreSearchOrderRequest;
use App\Http\Requests\UpdateSearchOrderRequest;
use App\Models\SearchOrder;



class orderService
{
    //新規受付数
    public function get_target_items()
    {
        //対象となる商品番号だけ取り出す
        $products = Product::select('product_code')->where('user_id', Auth::id())->get();
        $item_no = [];
        foreach ($products as $product) {
            $no = $product->product_code;
            array_push($item_no, $no);
        }
        //OrderDetailのitemNumberを管理している商品番号だけに絞り込む(narrow絞り込む)
        $narrow_datas = OrderDetail::select('orderNumber')->where('user_id', Auth::id())->whereIn('order_details.itemNumber', $item_no )->get();
        
        return($narrow_datas);
    }
}