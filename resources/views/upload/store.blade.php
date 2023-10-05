{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>楽天出荷報告</h1>
    
@stop

@section('content')
    {{-- コンテンツ --}}
    @foreach ( $results as $result )
        <p>{{ $result }}</p>
    @endforeach


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
    