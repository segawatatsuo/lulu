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
        //dd($param);
        //$results = Order::where('Order_familyName',$param)->get();
        $results =Order::where(DB::raw('CONCAT(Order_familyName, Order_firstName)'), 'like', '%'.$param.'%')->get();
        //dd($results);
        return view('search.index');
    }
}
