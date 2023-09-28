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
                            <td style="width: 50%">新規受付</td>
                            <td>{{ $count_new }}</td>
                        </tr>
                        <tr>
                            <td>出荷待ち</td>
                            <td>{{ $count_orderProgress }}</td>
                        </tr>
                        <tr>
                            <td>出荷済み</td>
                            <td>{{ $count_complete }}</td>
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
                            <td style="width: 50%">受注金額</td>
                            <td>{{ number_format($sum_requestPrice) }}</td>
                        </tr>
                        <tr>
                            <td>受注件数</td>
                            <td>{{ $count_order }}</td>
                        </tr>
                        <tr>
                            <td>出荷件数</td>
                            <td>{{ $count_complete }}</td>
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
                            <td style="width: 50%">登録商品数</td>
                            <td>{{ $items_count }}</td>
                        </tr>
                        <tr>
                            <td>保管在庫数</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>現在の不良在庫数量</td>
                            <td>0</td>
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
                        @if ($top3 != null and count($top3) != 0)
                            <tr>
                                <td>1.{{ mb_strimwidth($top3[0]->itemName, 0, 40, '...') }}</td>
                            </tr>
                            <tr>
                                <td>2.{{ mb_strimwidth($top3[1]->itemName, 0, 40, '...') }}</td>
                            </tr>
                            <tr>
                                <td>3.{{ mb_strimwidth($top3[2]->itemName, 0, 40, '...') }}</td>
                            </tr>
                        @else
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        @endif
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
                            <td style="width: 50%">楽天</td>
                            <td>{{ number_format($sum_requestPrice) }}</td>
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
