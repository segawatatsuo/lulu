{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>ダッシュボード</h1>
@stop

@section('content')
    {{-- コンテンツ --}}

    <div class="card-deck">

        <div class="card">
            <div class="card-header">
                <h5>受注状態</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><td>新規受付</td><td>16</td></tr>
                        <tr><td>出荷準備中</td><td>26</td></tr>
                        <tr><td>出荷済み</td><td>36</td></tr>
                    </tbody>
                  </table>
                
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>販売実績</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><td>受注金額</td><td>¥56,300</td></tr>
                        <tr><td>受注件数</td><td>8</td></tr>
                        <tr><td>出荷件数</td><td>7</td></tr>
                    </tbody>
                  </table>
                
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>商品・在庫状況</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><td>登録商品数</td><td>200</td></tr>
                        <tr><td>現在の在庫数量</td><td>100</td></tr>
                        <tr><td>現在の不良在庫数量</td><td>0</td></tr>
                    </tbody>
                  </table>
                
            </div>
        </div>

    </div>


@stop

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
