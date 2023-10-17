<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\Paginator;

class ItemController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->item = new Item();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::where('id',Auth::id());
        //get()を->paginate(10)に変更
        $items = Item::withSum('arrivals', 'arrival')->where('user_id',auth()->id())->paginate(10);
        return view('item/index', compact('items'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_rule = [
            'sku' => 'required',
            'product_name' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $registerItem = $this->item->InsertItem($request);
        
        if ($registerItem) {
            session()->flash('flash.success', '登録しました');
        } 
        return redirect()->route('item.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $item = Item::find( $id );
        return view('item.show',compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
 
        $item = Item::find($id);
        $item->sku = $request->input('sku');
        $item->product_name = $request->input('product_name');
        //$item->price = $request->input('price');
        //$item->stock = $request->input('stock');

        $validate_rule = [
            'sku' => 'required',
            'product_name' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $item->save();
        return redirect()->route('item.index')->with(['message' => '更新しました']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item=Item::find($id);
        $item->delete();
        return redirect(route('item.index'));
    }
}
