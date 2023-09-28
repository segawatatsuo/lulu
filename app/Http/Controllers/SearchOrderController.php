<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSearchOrderRequest;
use App\Http\Requests\UpdateSearchOrderRequest;
use App\Models\SearchOrder;
use App\Models\SearchOrderTemp;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrderDetail;
use Carbon\Carbon;

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
            'startDatetime' => '2023-09-27T00:00:00+0900', //期間検索開始日時
            'endDatetime' => '2023-09-27T23:59:59+0900', //期間検索終了日時
            //'startDatetime' => date("Y-m-d") . "T00:00:00+0900", //期間検索開始日時
            //'endDatetime' => date("Y-m-d") . "T23:59:59+0900", //期間検索終了日時

            'PaginationRequestModel:' => array( //ページングリクエストモデル
                'requestRecordsAmount : 1000', //1ページあたりの取得結果数.最大 1000 件まで指定可能
                'requestPage : 5', //リクエストページ番号
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
        //dd($orderNumberList);
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
        $not_exist_data = $query->where('user_id', Auth::id());
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

    public function getorder()
    {
        $user = User::find(Auth::id());
        define("RMS_SERVICE_SECRET", $user->rms_service_secret);
        define("RMS_LICENSE_KEY", $user->rms_license_key);
        define("AUTH_KEY", base64_encode(RMS_SERVICE_SECRET . ':' . RMS_LICENSE_KEY));

        $authkey = AUTH_KEY;
        $header = array(
            "Content-Type: application/json; charset=utf-8",
            "Authorization: ESA {$authkey}",
        );

        // ------------------------------------------------ パラメーター情報 

        //$order_numbers = SearchOrderTemp::all();
        //Orderテーブルに存在しないSearchOrderTempの値だけを取り出す
        $order_numbers = SearchOrderTemp::doesntHave('order')->get();
        $array = [];
        foreach($order_numbers as $num){
            array_push( $array ,$num->order_number );
        }
        $param = array(
            'orderNumberList' => $array,
            'version' => 7,
        );

        $url = "https://api.rms.rakuten.co.jp/es/2.0/order/getOrder/";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true); //POST送信
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param)); //jsonにエンコード
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($ch);
        curl_close($ch);
        $jsonstr = json_decode($xml, false);
        $Orders = $jsonstr->OrderModelList;
        //dd($Orders[0]->PackageModelList[0]->ItemModelList[0]->SkuModelList[0]->skuInfo);
        /*
        $orderNumber=$Orders[0]->orderNumber;
        $orderProgress=$Orders[0]->orderProgress;
        $zipCode1=$Orders[0]->OrdererModel->zipCode1;
        $SettlementModel=$Orders[0]->SettlementModel->settlementMethod;
        $ItemModelList=$Orders[0]->PackageModelList[0]->ItemModelList[0]->itemName;//商品内容（配列）
        */


        

        //注文者情報
        foreach ($Orders as $order) {


            $add_order = new Order();
            $user_id = Auth::id();
            $add_order->fill([
                'user_id' => $user_id,
                'orderNumber' => $order->orderNumber,
                'orderProgress' => $order->orderProgress,
                'subStatusName' => $order->subStatusName,
                'orderDatetime' => $order->orderDatetime,
                'shopOrderCfmDatetime' => $order->shopOrderCfmDatetime,
                'orderFixDatetime' => $order->orderFixDatetime,
                'orderDate' => substr($order->orderDatetime,0,10),
                'shippingInstDatetime' => $order->shippingInstDatetime,
                'shippingCmplRptDatetime' => $order->shippingCmplRptDatetime,
                'cancelDueDate' => $order->cancelDueDate,
                'deliveryDate' => $order->deliveryDate,
                'shippingTerm' => $order->shippingTerm,
                'remarks' => $order->remarks,
                'giftCheckFlag' => $order->giftCheckFlag,
                'severalSenderFlag' => $order->severalSenderFlag,
                'equalSenderFlag' => $order->equalSenderFlag,
                'isolatedIslandFlag' => $order->isolatedIslandFlag,
                'rakutenMemberFlag' => $order->rakutenMemberFlag,
                'carrierCode' => $order->carrierCode,
                'emailCarrierCode' => $order->emailCarrierCode,
                'orderType' => $order->orderType,
                'reserveNumber' => $order->reserveNumber,
                'reserveDeliveryCount' => $order->reserveDeliveryCount,
                'cautionDisplayType' => $order->cautionDisplayType,
                'cautionDisplayDetailType' => $order->cautionDisplayDetailType,
                'rakutenConfirmFlag' => $order->rakutenConfirmFlag,
                'goodsPrice' => $order->goodsPrice,
                'goodsTax' => $order->goodsTax,
                'postagePrice' => $order->postagePrice,
                'deliveryPrice' => $order->deliveryPrice,
                'paymentCharge' => $order->paymentCharge,
                'paymentChargeTaxRate' => $order->paymentChargeTaxRate,
                'totalPrice' => $order->totalPrice,
                'requestPrice' => $order->requestPrice,
                'couponAllTotalPrice' => $order->couponAllTotalPrice,
                'couponShopPrice' => $order->couponShopPrice,
                'couponOtherPrice' => $order->couponOtherPrice,
                'additionalFeeOccurAmountToUser' => $order->additionalFeeOccurAmountToUser,
                'additionalFeeOccurAmountToShop' => $order->additionalFeeOccurAmountToShop,
                'asurakuFlag' => $order->asurakuFlag,
                'drugFlag' => $order->drugFlag,
                'dealFlag' => $order->dealFlag,
                'membershipType' => $order->membershipType,
                'memo' => $order->memo,
                'operator' => $order->operator,
                'mailPlugSentence' => $order->mailPlugSentence,
                'modifyFlag' => $order->modifyFlag,
                'receiptIssueCount' => $order->receiptIssueCount,
                'receiptIssueHistoryList' => implode( $order->receiptIssueHistoryList ),
                'Order_zipCode1' => $order->OrdererModel->zipCode1,
                'Order_zipCode2' => $order->OrdererModel->zipCode2,
                'Order_prefecture' => $order->OrdererModel->prefecture,
                'Order_city' => $order->OrdererModel->city,
                'Order_subAddress' => $order->OrdererModel->subAddress,
                'Order_familyName' => $order->OrdererModel->familyName,
                'Order_firstName' => $order->OrdererModel->firstName,
                'Order_familyNameKana' => $order->OrdererModel->familyNameKana,
                'Order_firstNameKana' => $order->OrdererModel->firstNameKana,
                'Order_phoneNumber1' => $order->OrdererModel->phoneNumber1,
                'Order_phoneNumber2' => $order->OrdererModel->phoneNumber2,
                'Order_phoneNumber3' => $order->OrdererModel->phoneNumber3,
                'Order_emailAddress' => $order->OrdererModel->emailAddress,
                'Order_sex' => $order->OrdererModel->sex,
                'Order_birthYear' => $order->OrdererModel->birthYear,
                'Order_birthMonth' => $order->OrdererModel->birthMonth,
                'Order_birthDay' => $order->OrdererModel->birthDay,
                'settlementMethodCode' => $order->SettlementModel->settlementMethodCode,
                'settlementMethod' => $order->SettlementModel->settlementMethod,
                'rpaySettlementFlag' => $order->SettlementModel->rpaySettlementFlag,
                'cardName' => $order->SettlementModel->cardName,
                'cardNumber' => $order->SettlementModel->cardNumber,
                'cardOwner' => $order->SettlementModel->cardOwner,
                'cardYm' => $order->SettlementModel->cardYm,
                'cardPayType' => $order->SettlementModel->cardPayType,
                'cardInstallmentDesc' => $order->SettlementModel->cardInstallmentDesc,
                'deliveryName' => $order->DeliveryModel->deliveryName,
                'deliveryClass' => $order->DeliveryModel->deliveryClass,
                'usedPoint' => $order->PointModel->usedPoint,

                'basketId' => $order->PackageModelList[0]->basketId,
                'postagePrice' => $order->PackageModelList[0]->postagePrice,
                'postageTaxRate' => $order->PackageModelList[0]->postageTaxRate,
                'deliveryPrice' => $order->PackageModelList[0]->deliveryPrice,
                'deliveryTaxRate' => $order->PackageModelList[0]->deliveryTaxRate,
                'goodsTax' => $order->PackageModelList[0]->goodsTax,
                'goodsPrice' => $order->PackageModelList[0]->goodsPrice,
                'totalPrice' => $order->PackageModelList[0]->totalPrice,
                'noshi' => $order->PackageModelList[0]->noshi,
                'defaultDeliveryCompanyCode' => $order->PackageModelList[0]->defaultDeliveryCompanyCode,
                'packageDeleteFlag' => $order->PackageModelList[0]->packageDeleteFlag,

                'Sender_zipCode1' => $order->PackageModelList[0]->SenderModel->zipCode1,
                'Sender_zipCode2' => $order->PackageModelList[0]->SenderModel->zipCode2,
                'Sender_prefecture' => $order->PackageModelList[0]->SenderModel->prefecture,
                'Sender_city' => $order->PackageModelList[0]->SenderModel->city,
                'Sender_subAddress' => $order->PackageModelList[0]->SenderModel->subAddress,
                'Sender_familyName' => $order->PackageModelList[0]->SenderModel->familyName,
                'Sender_firstName' => $order->PackageModelList[0]->SenderModel->firstName,
                'Sender_familyNameKana' => $order->PackageModelList[0]->SenderModel->familyNameKana,
                'Sender_firstNameKana' => $order->PackageModelList[0]->SenderModel->firstNameKana,
                'Sender_phoneNumber1' => $order->PackageModelList[0]->SenderModel->phoneNumber1,
                'Sender_phoneNumber2' => $order->PackageModelList[0]->SenderModel->phoneNumber2,
                'Sender_phoneNumber3' => $order->PackageModelList[0]->SenderModel->phoneNumber3,
                'Sender_isolatedIslandFlag' => $order->PackageModelList[0]->SenderModel->isolatedIslandFlag,
                'taxRate' => $order->TaxSummaryModelList[0]->taxRate,
                'reqPrice' => $order->TaxSummaryModelList[0]->reqPrice,
                'reqPriceTax' => $order->TaxSummaryModelList[0]->reqPriceTax,
                'totalPrice' => $order->TaxSummaryModelList[0]->totalPrice,
                'paymentCharge' => $order->TaxSummaryModelList[0]->paymentCharge,
                'couponPrice' => $order->TaxSummaryModelList[0]->couponPrice,
                'point' => $order->TaxSummaryModelList[0]->point,
                'shoppingMallName' => '楽天'
            ]);
            $add_order->save();
        }

        //明細情報
        foreach ($Orders as $order) {
            $PackageModelList_array = $order->PackageModelList;
            foreach ($PackageModelList_array as $details) {
                $ItemModelList_array = $details->ItemModelList;
                foreach ($ItemModelList_array as $itemDetail) {

                    $add_detail = new OrderDetail();
                    $add_detail->fill([
                        'orderNumber' => $order->orderNumber,
                        'itemDetailId' => $itemDetail->itemDetailId,
                        'itemName' => $itemDetail->itemName,
                        'itemId' => $itemDetail->itemId,
                        'itemNumber' => $itemDetail->itemNumber,
                        'manageNumber' => $itemDetail->manageNumber,
                        'price' => $itemDetail->price,
                        'units' => $itemDetail->units,
                        'includePostageFlag' => $itemDetail->includePostageFlag,
                        'includeTaxFlag' => $itemDetail->includeTaxFlag,
                        'includeCashOnDeliveryPostageFlag' => $itemDetail->includeCashOnDeliveryPostageFlag,
                        'selectedChoice' => $itemDetail->selectedChoice,
                        'pointRate' => $itemDetail->pointRate,
                        'pointType' => $itemDetail->pointType,
                        'inventoryType' => $itemDetail->inventoryType,
                        'delvdateInfo' => $itemDetail->delvdateInfo,
                        'restoreInventoryFlag' => $itemDetail->restoreInventoryFlag,
                        'dealFlag' => $itemDetail->dealFlag,
                        'drugFlag' => $itemDetail->drugFlag,
                        'deleteItemFlag' => $itemDetail->deleteItemFlag,
                        'taxRate' => $itemDetail->taxRate,
                        'priceTaxIncl' => $itemDetail->priceTaxIncl,
                        'isSingleItemShipping' => $itemDetail->isSingleItemShipping,
                        'SkuModelList_skuInfo' => $itemDetail->SkuModelList[0]->skuInfo,
                        'SkuModelList_variantId' => $itemDetail->SkuModelList[0]->variantId,
                        'SkuModelList_merchantDefinedSkuId' => $itemDetail->SkuModelList[0]->merchantDefinedSkuId
                    ]);
                    $add_detail->save();
                }
            }
        }
    }


    public function test(Request $request)
    {
        //$all = SearchOrderTemp::all();
        //dd($all);

        //$not_exist_data = SearchOrderTemp::doesntHave('order')->get();
        //dd($not_exist_data);
        //print_r($not_exist_data[0]['order_number']);

            // データベースから、今日作成されたデータをを取り出す
            $today=Carbon::today();
            $dangos=Order::whereDate('orderDatetime', $today)->get();
            dd($dangos);

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
