{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>楽天出荷報告</h1>
    
@stop

@section('content')
    {{-- コンテンツ --}}

    <div class="table-wrap">
      <form method="post" action="{{ route('upload.store') }}">
        @csrf
        <button type="submit" class="btn btn-primary" onclick='return confirm("送信してよろしいですか？")'>送信</button>
        <table class="table table-striped">
            <thead>
              <tr>
                <th>選択</th>
                <th>受注番号</th><th>受注日</th><th>名前</th><th>モール</th>
                <th>商品発送日</th><th>発送伝票番号</th><th>配送会社</th>
                <th>配送会社名</th><th>ステータス</th><th>完了報告アップ日</th>
              </tr>
            </thead>
            <tbody>
              @foreach ( $orders as $order )
              <tr>
                <td><input type="checkbox" checked="checked" name="chk[]" value="{{ $order->id }}"></td>
                <td>{{ $order->orderNumber }}</td>
                <td>{{ $order->orderDatetime }}</td>
                <td>{{ $order->Order_familyName." ".$order->Order_firstName }}</td>
                <td>{{ $order->shoppingMallName }}</td>
                <td>{{ $order->dateOfShipment }}</td>
                <td>{{ $order->shippingDocumentNumber }}</td>
                <td>{{ $order->deliveryCompany }}</td>
                <td>{{ $order->deliveryCompanyName }}</td>
                <td>{{ $order->orderProgress }}</td>
                <td>{{ $order->cmpletionReportUpLoadDate }}</td>
                
              </tr>
              @endforeach

            </tbody>
          </table>
      </form>
    </div>

    {{-- $orders->links() --}}
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
