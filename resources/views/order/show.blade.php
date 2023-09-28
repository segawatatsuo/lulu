{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')


<div class="container small">

    @section('content_header')
        <h1>受注内容</h1>
        <h5>{{ $orders->orderNumber }} （ {{ $orders->orderProgress }} ）</h5>
        <p>{{ $orders->orderDatetime }}</p>
    @stop

    @section('content')
        {{-- コンテンツ --}}

        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">受注</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td style="width:30%">購買額</td>
                                    <td>{{ number_format($orders->goodsPrice) }}円</td>
                                </tr>
                                <tr>
                                    <td>ポイント・クーポン利用額</td>
                                    <td>{{ $orders->couponAllTotalPrice + $orders->usedPoint }}円</td>
                                </tr>


                                <tr>
                                    <td>総請求額</td>
                                    <td>{{ number_format($orders->requestPrice) }}円</td>
                                </tr>
                                <tr>
                                    <td>支払方法</td>
                                    <td>{{ $orders->settlementMethod }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->

                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">配送</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td style="width: 30%">配送方法</td>
                                    <td>{{ $orders->deliveryName }}</td>
                                </tr>
                                <tr>
                                    <td>お届け日時</td>
                                    <td>{{ $orders->deliveryDate }}{{ $orders->shippingTerm }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->

                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">備考</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{ $orders->remarks }}
                    </div>
                </div>
                <!-- /.card -->


            </div>



            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">注文者情報</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td style="width: 20%">名前</td>
                                    <td>{{ $orders->Order_familyName . $orders->Order_firstName }}({{ $orders->Order_familyNameKana . $orders->Order_firstNameKana }})
                                    </td>
                                </tr>
                                <tr>
                                    <td>住所</td>
                                    <td>{{ '〒' . $orders->Order_zipCode1 . '-' . $orders->Order_zipCode2 }}
                                        {{ $orders->Order_prefecture . $orders->Order_city . $orders->Order_subAddress }}</td>
                                </tr>
                                <tr>
                                    <td>電話</td>
                                    <td>{{ $orders->Order_phoneNumber1 . '-' . $orders->Order_phoneNumber2 . '-' . $orders->Order_phoneNumber3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>メール</td>
                                    <td>{{ $orders->Order_emailAddress }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">送付先情報</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td style="width: 20%">名前</td>
                                    <td>{{ $orders->Sender_familyName . $orders->Sender_firstName }}({{ $orders->Sender_familyNameKana . $orders->Sender_firstNameKana }})
                                    </td>
                                </tr>
                                <tr>
                                    <td>住所</td>
                                    <td>{{ '〒' . $orders->Sender_zipCode1 . '-' . $orders->Sender_zipCode2 }}
                                        {{ $orders->Sender_prefecture . $orders->Sender_city . $orders->Sender_subAddress }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>電話</td>
                                    <td>{{ $orders->Sender_phoneNumber1 . '-' . $orders->Sender_phoneNumber2 . '-' . $orders->Sender_phoneNumber3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>離島</td>
                                    <td>
                                        @if ($orders->isolatedIslandFlag == 1)
                                            {{ '離島です' }}
                                        @else
                                            {{ '離島ではありません' }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->



            </div>

            <div class="col-md-12">
                <table class="table table-bordered" style="background-color: white">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" style="width: 40%">商品情報</th>
                            <th scope="col" style="width: 20%">選択肢</th>
                            <th scope="col" style="width: 16%">バリエーション</th>
                            <th scope="col" style="width: 8%">単価</th>
                            <th scope="col" style="width: 8%">個数</th>
                            <th scope="col" style="width: 8%">金額</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($details as $item)
                            <tr>
                                <td scope="row">{{ $item->itemName . '(' . $item->itemNumber . ')' }}</th>
                                <td scope="row">{{ $item->selectedChoice }}</td>
                                <td scope="row">{{ $item->SkuModelList_skuInfo }}</td>
                                <td scope="row">{{ number_format($item->price) }}円</td>
                                <td scope="row">{{ number_format($item->units) }}</td>
                                <td scope="row">{{ number_format($item->priceTaxIncl) }}円</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>





        @stop


    </div>

    @section('css')
        {{-- ページごとCSSの指定
    <link rel="stylesheet" href="/css/xxx.css">
    --}}
    @stop

    @section('js')
        <script>
            console.log('ページごとJSの記述');
        </script>
    @stop
