{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>ダッシュボード</h1>
@stop

@section('content')
    {{-- コンテンツ --}}

    <blockquote class="quote-info mt-0">
        <h5 id="tip">お知らせ</h5>
        <p>2023-9-15 準備中</p>
    </blockquote>


    <div class="card-columns">
        <div class="card card-info">
            <div class="card-header">
                <h5>受注状態</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>新規受付</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>出荷準備中</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>出荷済み</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h5>販売実績</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>受注金額</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>受注件数</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>出荷件数</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h5>商品・在庫状況</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>登録商品数</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>現在の在庫数量</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>現在の不良在庫数量</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>


        <div class="card card-info">
            <div class="card-header">
                <h5>売れ筋商品</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>商品名１</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>商品名２</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>商品名３</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h5>モール別売上</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>楽天</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Yahoo</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Amazon</td>
                            <td></td>
                        </tr>
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
    <style>
        blockquote {
            background-color: #fff;
            border-left: .7rem solid #007bff;
            margin: 1.5em .0rem;
            padding: .5em .7rem;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('ページごとJSの記述');
    </script>
@stop
