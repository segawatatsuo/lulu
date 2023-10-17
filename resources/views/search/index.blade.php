{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>検索結果</h1>
@stop

@section('content')
    {{-- コンテンツ --}}

    <div>

        <table class="table table-striped">

            <thead>
                <tr>
                    <th style="width: 15%">登録日</th>
                    <th style="width: 15%">SKU</th>
                    <th style="width: 41%">商品名</th>
                    <th style="width: 7%"></th>
                    <th style="width: 7%"></th>
                </tr>
            </thead>

            <tbody>

                    <tr>


                        <td>

                        </td>
                    </tr>

            </tbody>
        </table>

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
