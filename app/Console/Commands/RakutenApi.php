<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

class RakutenApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';
    protected $signature = 'RakutenApi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rakutenデータの取得と更新';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
        //SearchOrderTempテーブルの全レコードを削除。
        SearchOrderTemp::truncate();
        // ------------------------------------------------ 基礎情報
        //$user = User::find(Auth::id());
        $user = User::find(2);


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
            'orderProgressList' => [ 100, 200, 300, 400, 500, 600, 700, 800, 900 ],
            'dateType' => 1, //1: 注文日
            //'startDatetime' => '2023-10-14T00:00:00+0900',
            //'endDatetime' => '2023-10-20T23:59:59+0900',
            
            'startDatetime' => date('Y-m-d', strtotime('-1 day')) . "T00:00:00+0900", //期間検索開始日時(昨日)
            'endDatetime' => date("Y-m-d") . "T23:59:59+0900", //期間検索終了日時
            'PaginationRequestModel' => [
                'requestRecordsAmount' => 1000,
                'requestPage' => 1,
                'SortModel'=>['sortColumn' => 1,'sortDirection' => 2]
            ]
        );
        $data = json_encode($param);

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

        $orderNumberList = $jsonstr->orderNumberList; //orderNumberListはオブジェクト型
        //Tempテーブルにインポート

        //$user_id = Auth::id();
        $user_id = 2;
        
        
        foreach ($orderNumberList as $str) {
            $orderNumber = mb_convert_encoding($str, "utf-8");
            $SearchOrderTemp = new SearchOrderTemp();
            $SearchOrderTemp->fill(['order_number' => $orderNumber]);
            $SearchOrderTemp->fill(['user_id' => $user_id]);
            $SearchOrderTemp->save();
        }
        //SearchOrderTempにあって、searchorderに存在しないもの（=新規レコード）
        $query = SearchOrderTemp::doesntHave('search_order')->get();
        //該当userだけに絞り込む

        //$not_exist_data = $query->where('user_id', Auth::id());
        $not_exist_data = $query->where('user_id', 2);

        $search_orders = json_decode($not_exist_data, true);

        foreach ($search_orders as $item) {
            //print_r($item['order_number']);
            //print("<br>");
            $SearchOrder = new SearchOrder();
            $SearchOrder->fill(['order_number' => $item['order_number']]);
            //$SearchOrder->fill(['user_id' => Auth::id()]);
            $SearchOrder->fill(['user_id' => 2]);
            $SearchOrder->save();
        }

        //-------------------------------------------　GetOrder　

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

        //注文者情報
        foreach ($Orders as $order) {
            //配送番号等があるかどうか。配列なのでカウント
            if(count($order->PackageModelList[0]->ShippingModelList)){
                $shippingDetailId = $order->PackageModelList[0]->ShippingModelList[0]->shippingDetailId;
                $shippingNumber = $order->PackageModelList[0]->ShippingModelList[0]->shippingNumber;
                $deliveryCompany = $order->PackageModelList[0]->ShippingModelList[0]->deliveryCompany;
                $deliveryCompanyName = $order->PackageModelList[0]->ShippingModelList[0]->deliveryCompanyName;
                $shippingDate = $order->PackageModelList[0]->ShippingModelList[0]->shippingDate;
            }else{
                $shippingDetailId = NULL;
                $shippingNumber = NULL;
                $deliveryCompany = NULL;
                $deliveryCompanyName = NULL;
                $shippingDate = NULL;
            }
            $add_order = new Order();

            //$user_id = Auth::id();
            $user_id = 2;

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
                'shoppingMallName' => '楽天',

                'shippingDetailId' => $shippingDetailId,
                'shippingDocumentNumber' => $shippingNumber,
                'deliveryCompany' => $deliveryCompany,
                'deliveryCompanyName' => $deliveryCompanyName,
                'dateOfShipment' => $shippingDate,
            ]);
            $add_order->save();
        }

        //明細情報
        foreach ($Orders as $order) {

            //$user_id = Auth::id();
            $user_id = 2;

            $PackageModelList_array = $order->PackageModelList;
            foreach ($PackageModelList_array as $details) {
                $ItemModelList_array = $details->ItemModelList;
                foreach ($ItemModelList_array as $itemDetail) {
                    if(empty($itemDetail->SkuModelList)){
                        $SkuModelList_skuInfo = "";
                        $SkuModelList_variantId = "";
                        $SkuModelList_merchantDefinedSkuId = "";
                    }else{
                        $SkuModelList_skuInfo = $itemDetail->SkuModelList[0]->skuInfo;
                        $SkuModelList_variantId = $itemDetail->SkuModelList[0]->variantId;
                        $SkuModelList_merchantDefinedSkuId = $itemDetail->SkuModelList[0]->merchantDefinedSkuId;
                    }
                    $add_detail = new OrderDetail();
                    $add_detail->fill([
                        'orderNumber' => $order->orderNumber,
                        'user_id' => $user_id,
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

                        'SkuModelList_skuInfo' => $SkuModelList_skuInfo,
                        'SkuModelList_variantId' => $SkuModelList_variantId,
                        'SkuModelList_merchantDefinedSkuId' => $SkuModelList_merchantDefinedSkuId,
                    ]);
                    $add_detail->save();
                }
            }
        }


    }
}
