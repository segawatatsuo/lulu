{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>商品情報</h1>
    <button type="button" class="btn btn-primary" onclick="location.href='{{ route('item.create') }}'">新規登録</button>
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
                    <th style="width: 5%" class="text-right">入庫数</th>
                    <th style="width: 5%" class="text-right">保管数</th>
                    <th style="width: 5%" class="text-right">価格</th>
                    <th style="width: 7%"></th>
                    <th style="width: 7%"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">{{ number_format($item->arrivals_sum_arrival) }}</td>
                        <td class="text-right">{{ number_format($item->quantity) }}</td>
                        <td class="text-right">{{ number_format($item->price) }}</td>


                        <td>
                            <form action="{{ route('item.show', ['id'=>$item->id]) }}" method="post">
                                @csrf
                                <input type="submit" value="編集" class="btn btn-info">
                            </form>
                        </td>


                        <td>
                            <form action="{{ route('item.destroy', ['id'=>$item->id]) }}" method="post">
                                @csrf
                                <input type="submit" value="削除" class="btn btn-danger"
                                    onclick='return confirm("削除しますか？");'>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    {{ $items->links() }}
    
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
