<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeitem_detailRequest;
use App\Http\Requests\Updateitem_detailRequest;
use App\Models\item_detail;

class ItemDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeitem_detailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeitem_detailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\item_detail  $item_detail
     * @return \Illuminate\Http\Response
     */
    public function show(item_detail $item_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\item_detail  $item_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(item_detail $item_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateitem_detailRequest  $request
     * @param  \App\Models\item_detail  $item_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Updateitem_detailRequest $request, item_detail $item_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\item_detail  $item_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(item_detail $item_detail)
    {
        //
    }
}
