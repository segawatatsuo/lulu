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
                    <th style="width: 15%">商品番号</th>
                    <th style="width: 41%">商品名</th>
                    <th style="width: 5%" class="text-right">価格</th>
                    <th style="width: 7%"></th>
                    <th style="width: 7%"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td class="text-right">{{ number_format($product->product_price) }}</td>


                        <td>
                            <form action="{{ route('product.show', ['id'=>$product->id]) }}" method="post">
                                @csrf
                                <input type="submit" value="編集" class="btn btn-info">
                            </form>
                        </td>


                        <td>
                            <form action="{{ route('product.destroy', ['id'=>$product->id]) }}" method="post">
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

    {{ $products->links() }}
    
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
