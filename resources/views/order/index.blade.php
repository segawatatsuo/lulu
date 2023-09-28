{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>受注状況</h1>
@stop

@section('content')
    {{-- コンテンツ --}}

    <div class="table-wrap">

        <table class="table table-striped">
            <thead>
              <tr>
                <th>受注番号</th><th>受注日</th><th>モール</th><th>購入者</th>
                <th>商品計</th><th>外税合計</th><th>発送料</th>
                <th>手数料</th><th>他費用</th><th>クーポン利用総額</th><th>総合計</th><th>送り先名</th>
                <th>送り先〒</th><th>送り先住所</th><th>発送方法</th><th>支払方法</th><th>発送伝票番号</th>
                <th>備考</th>

              </tr>
            </thead>
            <tbody>
              @foreach ( $orders as $order )
              <tr>
                <td><a href="{{ route('order.show', ['id'=>$order->id]) }}" >{{ $order->orderNumber }}</a></td>
                <td>{{ $order->orderDatetime }}</td>
                
                <td>{{ $order->shoppingMallName }}</td>
                <td>{{ $order->Order_familyName.$order->Order_firstName }}</td>

                <td><p class="text-right">{{ number_format($order->goodsPrice) }}</p></td>

                <td><p class="text-right">{{ number_format($order->goodsTax) }}</p></td>
                <td><p class="text-right">{{ number_format($order->postagePrice) }}</p></td>
                <td><p class="text-right">{{ number_format($order->deliveryPrice) }}</p></td>
                <td><p class="text-right">{{ number_format($order->paymentChargeTaxRate) }}</p></td>
                <td><p class="text-right">{{ number_format($order->couponAllTotalPrice) }}</p></td>
                <td><p class="text-right">{{ number_format($order->requestPrice) }}</p></td>

                <td>{{ $order->Sender_familyName.$order->Sender_firstName }}</td>
                <td>{{ $order->Sender_zipCode1."-".$order->Sender_zipCode2 }}</td>
                <td>{{ $order->Sender_prefecture.$order->Sender_city.$order->Sender_subAddress }}</td>
                <td>{{ $order->deliveryName }}</td>
                <td>{{ $order->settlementMethod }}</td>
                <td>{{ $order->shippingDocumentNumber }}</td>
                <td>{{ $order->remarks }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>

    </div>

    {{ $orders->links() }}
@stop

@section('css')
    {{-- ページごとCSSの指定
    <link rel="stylesheet" href="/css/xxx.css">
    --}}
    <style>
    .table-wrap {
        overflow-x: scroll;
      }
      .table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
      }
      </style>
@stop

@section('js')
    <script>
        console.log('ページごとJSの記述');
    </script>
@stop
