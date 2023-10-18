{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

<!--
<div class="container small">
-->
    @section('content_header')
        <h1>商品編集</h1>
    @stop

    @section('content')
        {{-- コンテンツ --}}

        <form action="{{ route('product.update', $product->id) }}" method="POST">
            @csrf
            {{-- @method('PATCH') --}}
            <fieldset>
                <div class="form-group">

                    @if (session('flash'))
                        @foreach (session('flash') as $key => $item)
                            <div class="alert alert-{{ $key }}">{{ session('flash.' . $key) }}</div>
                        @endforeach
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            入力に問題があります。再入力してください。
                        </div>
                    @endif


                    <label for="book_name">{{ __('商品番号') }}<span
                            class="badge badge-danger ml-2">{{ __('必須') }}</span></label>
                    <input type="text" class="form-control" name="product_code" id="product_code"
                        value="{{ $product->product_code }}">

                    <label for="book_name">{{ __('商品名') }}<span
                            class="badge badge-danger ml-2">{{ __('必須') }}</span></label>
                    <input type="text" class="form-control" name="product_name" id="product_name"
                        value="{{ $product->product_name }}">


                    <label for="book_name">{{ __('価格') }}<span
                            class="badge badge-danger ml-2">{{ __('必須') }}</span></label>
                    <input type="text" class="form-control" name="product_price" id="product_price" placeholder="半角数字"
                        value="{{ $product->product_price }}">


                    <table>
                        <thead>
                            <tr>
                                <td>SKU</td>
                                <td>商品名</td>
                                <td>数量</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td><input type="text" class="form-control" name="sku" id="sku"
                                            value="{{ $item->sku }}"></td>
                                    <td><input type="text" class="form-control" name="sku" id="sku"
                                            value="{{ $item->product_name }}"></td>
                                    <td><input type="text" class="form-control" name="sku" id="sku"
                                            value="{{ $item->quantity }}"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td><input type="text" class="form-control" name="name" id="name" value="">
                                </td>
                                <td><input type="text" class="form-control" name="name" id="name" value="">
                                </td>
                                <td><input type="text" class="form-control" name="name" id="name" value="">
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('product.index') }}" class="btn btn-outline-secondary" role="button">
                            <i class="fa fa-reply mr-1" aria-hidden="true"></i>{{ __('一覧画面へ') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            {{ __('登録') }}
                        </button>
                    </div>

            </fieldset>
        </form>
        
    @stop

<!--
</div>
-->

@section('css')
    {{-- ページごとCSSの指定
    <link rel="stylesheet" href="/css/xxx.css">
    --}}
@stop

@section('js')
    <script>
        $("#btn3").click(function() {
            var data = [];
            var tr = $("table tbody tr td input:text"); //tbody以下の全行を取得
            for (var i = 0, l = tr.length; i < l; i++) {
                var cells = tr.eq(i).children(); //1行目から順にtbody内の列を取得
                for (var j = 0, m = cells.length; j < m; j++) {
                    if (typeof data[i] == "undefined")
                        data[i] = [];
                    data[i][j] = cells.eq(j).text(); //i行目j列の文字列を取得
                }
            }
            alert(data);
        });
    </script>

@stop
