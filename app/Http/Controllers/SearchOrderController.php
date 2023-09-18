<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSearchOrderRequest;
use App\Http\Requests\UpdateSearchOrderRequest;
use App\Models\SearchOrder;
use App\Models\SearchOrderTemp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SearchOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //SearchOrderTempテーブルの全レコードを削除して空にする。
        SearchOrderTemp::truncate();

        // ------------------------------------------------ 基礎情報
        $user = User::find(Auth::id());
        //$rms_service_secret = $user->rms_service_secret;
        //$rms_license_key = $user->rms_license_key;
        //define("RMS_SERVICE_SECRET", "SP256060_QzXjO8cLgxtFSuGy");
        //define("RMS_LICENSE_KEY", "SL256060_51mJW265mqrL5EQ6");

        define("RMS_SERVICE_SECRET", $user->rms_service_secret);
        define("RMS_LICENSE_KEY", $user->rms_license_key);
        define("AUTH_KEY", base64_encode(RMS_SERVICE_SECRET . ':' . RMS_LICENSE_KEY));

        $authkey = AUTH_KEY;
        $header = array(
            "Content-Type: application/json; charset=utf-8",
            "Authorization: ESA {$authkey}",
        );

        // ------------------------------------------------ パラメーター情報 連想配列
        $param = array(
            'dateType' => 1, //1: 注文日
            //'startDatetime' => '2023-09-05T00:00:00+0900', //期間検索開始日時
            //'endDatetime' => '2023-09-12T00:00:00+0900', //期間検索終了日時
            'startDatetime' => date("Y-m-d") . "T00:00:00+0900", //期間検索開始日時
            'endDatetime' => date("Y-m-d") . "T23:59:59+0900", //期間検索終了日時


            'PaginationRequestModel:' => array( //ページングリクエストモデル
                'requestRecordsAmount : 100', //1ページあたりの取得結果数.最大 1000 件まで指定可能
                'requestPage : 1', //リクエストページ番号
                'SortModelList:' => array(
                    "sortColumn : 1", //並び替え項目.1: 注文日時
                    "sortDirection : 1" //並び替え方法.1: 昇順（小さい順、古い順）
                )
            ),
        );
        //searchOrder
        //この機能を利用すると、楽天ペイ注文の「注文検索」を行うことができます。こちらは同期処理となります。
        //検索結果が 15000 件以上の場合、15001 件目以降の受注番号は取得できません。

        $url = "https://api.rms.rakuten.co.jp/es/2.0/order/searchOrder/";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true); //POST送信
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param)); //jsonにエンコード
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($ch);
        curl_close($ch);
        $jsonstr = json_decode($xml, false);

        $orderNumberList = $jsonstr->orderNumberList; //orderNumberListはオブジェクト型（配列ではない）


        //Tempテーブルにインポート
        $user_id = Auth::id();
        foreach ($orderNumberList as $str) {
            $orderNumber = mb_convert_encoding($str, "utf-8");
            //$search_order_temp = SearchOrderTemp::create(['order_number' => $orderNumber]);
            $SearchOrderTemp = new SearchOrderTemp();
            $SearchOrderTemp->fill(['order_number' => $orderNumber]);
            $SearchOrderTemp->fill(['user_id' => $user_id]);
            $SearchOrderTemp->save();
        }

        //SearchOrderTempにあって、searchorderに存在しないもの（=新規レコード）
        $query = SearchOrderTemp::doesntHave('search_order')->get();
        //該当userだけに絞り込む
        $not_exist_data=$query->where('user_id',Auth::id()); 

        $search_orders = json_decode($not_exist_data, true);

        foreach ($search_orders as $item) {
            print_r($item['order_number']);
            print("<br>");
            //$search_order = SearchOrder::create(['order_number' => $item['order_number']]);
            $SearchOrder = new SearchOrder();
            $SearchOrder->fill(['order_number' => $item['order_number']]);
            $SearchOrder->fill(['user_id' => Auth::id()]);
            $SearchOrder->save();
        }
    }

    public function test()
    {
        $all = SearchOrderTemp::all();
        //dd($all);
        $hoge = SearchOrderTemp::first();
        //select * from `search_order_temps` where not exists (select * from `search_orders` where `search_order_temps`.`order_number` = `search_orders`.`order_number`)
        //$not_exist_data = SearchOrderTemp::doesntHave('search_order')->select('order_number')->get();
        $not_exist_data = SearchOrderTemp::doesntHave('search_order')->get();
        print_r($not_exist_data[0]['order_number']);
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
     * @param  \App\Http\Requests\StoreSearchOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSearchOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SearchOrder  $searchOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SearchOrder $searchOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SearchOrder  $searchOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SearchOrder $searchOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSearchOrderRequest  $request
     * @param  \App\Models\SearchOrder  $searchOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSearchOrderRequest $request, SearchOrder $searchOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SearchOrder  $searchOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchOrder $searchOrder)
    {
        //
    }
}
