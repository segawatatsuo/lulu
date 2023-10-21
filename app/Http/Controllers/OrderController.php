<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\orderService;

class OrderController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $odsv;
    public function __construct()
    {
        $this->middleware('auth');
        $this->odsv = new orderService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //商品番号
        /*
        $products = Product::select('product_code')->where('user_id', Auth::id())->get();
        $item_no = [];
        foreach ($products as $product) {
            $no = $product->product_code;
            array_push($item_no, $no);
        }
        //OrderDetailのitemNumberを管理している商品番号だけに絞り込む(narrow絞り込む)
        $narrow_datas = OrderDetail::select('orderNumber')->where('user_id', Auth::id())->whereIn('order_details.itemNumber', $item_no )->get();
        */

        $narrow_datas = $this->odsv->get_target_items();
        $orders = Order::whereIn('orderNumber', $narrow_datas)->orderBy('orderDate', 'desc')->paginate(10);
        return view('order/index', compact('orders'));
    }

    public function shipping()
    {
        //商品番号
        /*
        $products = Product::select('product_code')->where('user_id', Auth::id())->get();
        $item_no = [];
        foreach ($products as $product) {
            $no = $product->product_code;
            array_push($item_no, $no);
        }
        //OrderDetailのitemNumberがhx07だけに絞り込む(narrow絞り込む)
        $narrow_datas = OrderDetail::select('orderNumber')->where('user_id', Auth::id())->whereIn('order_details.itemNumber', $item_no )->get();
        */
        $narrow_datas = $this->odsv->get_target_items();
        $orders = Order::whereIn('orderNumber', $narrow_datas)->where('user_id', Auth::id())->where('dateOfShipment', '>=', '2000/01/01')->orderBy('orderDate', 'desc')->paginate(10);
        return view('shipping/index', compact('orders'));
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
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //主キーではないのでfind($id)ではnullを返してくるのでwhereを使う。
        $orders = Order::where('id', $id)->first();
        $no = $orders->orderNumber;
        $details = OrderDetail::with('order')->where('orderNumber', $no)->get();
        return view('order.show', compact('orders', 'details'));
    }

    public function upload()
    {
        //商品番号
        /*
        $products = Product::select('product_code')->where('user_id', Auth::id())->get();
        $item_no = [];
        foreach ($products as $product) {
            $no = $product->product_code;
            array_push($item_no, $no);
        }
        $narrow_datas = OrderDetail::select('orderNumber')->where('user_id', Auth::id())->whereIn('order_details.itemNumber', $item_no )->get();
        */
        $narrow_datas = $this->odsv->get_target_items();
        $orders = Order::whereIn('orderNumber', $narrow_datas)->where([
            ['user_id', Auth::id()],
            ['dateOfShipment', '>', '2000/01/01'],
            ['orderProgress', '300'],
            ['shippingDocumentNumber', '<>', NULL],
            ['deliveryCompany', '<>', NULL],
            ['deliveryCompanyName', '<>', NULL],
            ['cmpletionReportUpLoadDate', '=', NULL],
        ])->orderBy('orderDatetime', 'desc')->paginate(10);
        
        /*
        $orders = Order::where([
            ['user_id', Auth::id()],
            ['dateOfShipment', '>', '2000/01/01'],
            ['orderProgress', '300'],
            ['shippingDocumentNumber', '<>', NULL],
            ['deliveryCompany', '<>', NULL],
            ['deliveryCompanyName', '<>', NULL],
            ['cmpletionReportUpLoadDate', '=', NULL],
        ])->orderBy('orderDatetime', 'desc')->paginate(10);
        */

        return view('upload.index', compact('orders'));
    }

    public function store(StoreOrderRequest $request)
    {
        $input_data = $request->all();
        $id_list = [];
        //更新対象一覧にチェックのある人を取り出す
        if (isset($input_data['chk'])) {
            foreach ($input_data['chk'] as $key => $value) {
                array_push($id_list, $value);
            }
        }
        //idから必要な情報を取り出したリストを作成
        $ship_list = [];
        foreach ($id_list as $id) {
            $order = Order::where('id', $id)->first();
            $orderNumber = $order->orderNumber;
            $basketId = $order->basketId;
            $deliveryCompany = $order->deliveryCompany;
            $shippingNumber = $order->shippingDocumentNumber;
            $shippingDate = $order->dateOfShipment;
            array_push($ship_list, [$orderNumber, $basketId, $deliveryCompany, $shippingNumber, $shippingDate]);
        }
        //dd($ship_list[0]);
        /*
array:5 [▼
  0 => "419133-20230926-0183609554"
  1 => 1922064049
  2 => "1003"
  3 => "628089764772"
  4 => "2023-09-28"
]
*/

        // ------------------------------------------------ 基礎情報
        $user = User::find(Auth::id());
        define("RMS_SERVICE_SECRET", $user->rms_service_secret);
        define("RMS_LICENSE_KEY", $user->rms_license_key);
        define("AUTH_KEY", base64_encode(RMS_SERVICE_SECRET . ':' . RMS_LICENSE_KEY));

        $authkey = AUTH_KEY;
        $header = array(
            "Content-Type: application/json; charset=utf-8",
            "Authorization: ESA {$authkey}",
        );

        $results = [];

        foreach ($ship_list as $data) {
            $orderNumber = $data[0];
            $basketId = $data[1];
            $deliveryCompany = $data[2];
            $shippingNumber = $data[3];
            $shippingDate = $data[4];
            // ------------------------------------------------ パラメーター情報 連想配列
            $param = array(
                'orderNumber' => $orderNumber, //注文番号
                'BasketidModelList' =>
                [
                    [
                        'basketId' => $basketId,
                        'ShippingModelList' =>
                        array([
                            //'shippingDetailId' => null,
                            'deliveryCompany' => $deliveryCompany,
                            'shippingNumber' => $shippingNumber,
                            'shippingDate' => $shippingDate,
                        ])
                    ]
                ]
            );
            //dd($param);
            $data = json_encode($param);
            //$data = $param;
            //dd($data);
            /*"{
"orderNumber":"419133-20230926-0183609554",
"BasketidModelList":{
	"basketId":1922064049,
	"ShippingModelList":{
		"shippingDetailId":null,
		"deliveryCompany":"1003",
		"shippingNumber":"628089764772",
		"shippingDate":"2023-09-28"
		}
	}
} "
*/


            $url = "https://api.rms.rakuten.co.jp/es/2.0/order/updateOrderShipping/";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xml = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);


            if ($httpcode == 200) {
                array_push($results, $orderNumber . "...ok");
                //DB更新
                $order = Order::where('orderNumber', $orderNumber)->first();
                $order->cmpletionReportUpLoadDate = new Carbon();
                $order->save();
            } elseif ($httpcode == 401) {
                array_push($results, $orderNumber . "...Un-Authorised (APIの使用許可がありません)");
            } elseif ($httpcode == 400) {
                array_push($results, $orderNumber . "...Bad Request (リクエストが不正です)");
            } elseif ($httpcode == 404) {
                array_push($results, $orderNumber . "...Not Found (Request-URI に一致するものを見つけられません)");
            } elseif ($httpcode == 405) {
                array_push($results, $orderNumber . "...Method Not Allowed (許可されていないメソッドです)");
            } elseif ($httpcode == 500) {
                array_push($results, $orderNumber . "...Internal Server Error (サーバ内部にエラーが発生)");
            } elseif ($httpcode == 503) {
                array_push($results, $orderNumber . "...Service Unavailable (サービスが一時的に過負荷やメンテナンスで使用不可能)");
            }
        }
        return view('upload.store', compact('results'));
        //return redirect('todofuken/tokyo');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
