<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArrivalRequest;
use App\Http\Requests\UpdateArrivalRequest;
use Illuminate\Http\Request;
use App\Models\Arrival;
use App\Models\Item;

class ArrivalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->item = new Arrival();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function index()
    {
        $items = Item::where('user_id',auth()->id())->get();
        return view("arrival.index",compact('items'));
    }
*/
    public function index()
    {
        /*
        $items = Item::withSum('arrivals', 'arrival')->get();
        $items = Item::where('user_id',auth()->id())->get();
        */
        $items = Item::withSum('arrivals', 'arrival')->where('user_id',auth()->id())->get();
        //$items = Item::where('user_id',auth()->id())->get();

        return view("arrival.index",compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('arrival.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArrivalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $queryParameters = $_POST['postId'];

        $validate_rule = [
            'arrival' => 'required|numeric',
        ];
        $this->validate($request, $validate_rule);

        $registerItem = $this->item->InsertItem($request,$queryParameters);
        
        if ($registerItem) {
            session()->flash('flash.success', '登録しました');
        } 
        return redirect()->route('arrival.index');
        //return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arrival  $arrival
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::find( $id );//個別
        $arrivals = Arrival::where('item_id', $id)->get();//全件
        return view('arrival.show',compact('item','arrivals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arrival  $arrival
     * @return \Illuminate\Http\Response
     */
    public function edit(Arrival $arrival)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArrivalRequest  $request
     * @param  \App\Models\Arrival  $arrival
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArrivalRequest $request, Arrival $arrival)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arrival  $arrival
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arrival $arrival)
    {
        //
    }
}
