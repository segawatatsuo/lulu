<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class SearchController extends Controller
{
    public function index(Request $request){
        $param = $request->adminlteSearch;
        $results =Order::where(DB::raw('CONCAT(Order_familyName, Order_firstName)'), 'like', '%'.$param.'%')->orwhere('orderNumber','like', '%'.$param.'%')->orwhere('orderDate','like', '%'.$param.'%')->orwhere('Sender_prefecture','like', '%'.$param.'%')->orwhere('Sender_city','like', '%'.$param.'%')->orderBy('orderDate','asc')->get();
        return view('search.index',compact('results'));
    }
}
