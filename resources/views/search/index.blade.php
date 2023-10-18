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
                    <th style="width: 10%">注文日</th>
                    <th style="width: 20%">注文番号</th>
                    <th style="width: 15%">氏名</th>
                    <th style="width: 55%">送付先</th>
                </tr>
            </thead>

            <tbody>
                    @foreach ( $results as $result )
                        
                    
                    <tr>
                        <td>{{ $result->orderDate }}</td>
                        <td><a href="{{ route('order.show',['id' => $result->id ]) }}">{{ $result->orderNumber }}</a></td>
                        <td>{{ $result->Order_familyName }}{{ $result->Order_firstName }}</td>
                        <td>{{ $result->Sender_prefecture }}{{ $result->Sender_city }}{{ $result->Sender_subAddress }}</td>
                    </tr>
                    @endforeach
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
