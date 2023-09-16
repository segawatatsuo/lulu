{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>出荷状況</h1>
@stop

@section('content')
    {{-- コンテンツ --}}

    <div class="table-wrap">

        <table class="table table-striped">
            <thead>
              <tr>
                <th>受注日</th><th>受注番号</th><th>モール</th><th>購入者</th>
                <th>商品計</th><th>税金</th><th>発送料</th>
                <th>手数料</th><th>他費用</th><th>ポイント</th><th>総合計</th><th>送り先名</th>
                <th>送り先〒</th><th>送り先住所</th><th>発送方法</th><th>支払方法</th><th>発送伝票番号</th>
                <th>備考</th>

              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2022-6-10</td>
                <td>12345</td>
                <td>楽天</td>
                <td>楽天太郎</td>
                <td>2,500</td>
                <td>250</td>
                <td>1,000</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>3,750</td>
                <td>楽天花子</td>
                <td>250-0001</td>
                <td>神奈川県横浜市磯子区3-1-2スカイパーク205</td>
                <td>佐川急便</td>
                <td>クレジットカード</td>
                <td>100-900-300-6789</td>
                <td></td>
              </tr>




            </tbody>
          </table>

    </div>


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
