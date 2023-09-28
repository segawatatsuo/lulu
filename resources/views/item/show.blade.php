{{-- vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')


<div class="container small">

    @section('content_header')
        <h1>商品編集</h1>
    @stop

    @section('content')
        {{-- コンテンツ --}}



        <form action="{{ route('item.update', $item->id) }}" method="POST">
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


                    <label for="book_name">{{ __('SKU') }}<span
                            class="badge badge-danger ml-2">{{ __('必須') }}</span></label>
                    <input type="text" class="form-control" name="sku" id="sku" value="{{ $item->sku }}">

                    <label for="book_name">{{ __('商品名') }}<span
                            class="badge badge-danger ml-2">{{ __('必須') }}</span></label>
                    <input type="text" class="form-control" name="product_name" id="product_name"
                        value="{{ $item->product_name }}">


                    <label for="book_name">{{ __('価格') }}<span
                            class="badge badge-danger ml-2">{{ __('必須') }}</span></label>
                    <input type="text" class="form-control" name="price" id="price" placeholder="半角数字"
                        value="{{ $item->price }}">


                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('item.index') }}" class="btn btn-outline-secondary" role="button">
                            <i class="fa fa-reply mr-1" aria-hidden="true"></i>{{ __('一覧画面へ') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            {{ __('登録') }}
                        </button>
                    </div>


            </fieldset>
        </form>


    @stop


</div>

@section('css')
    {{-- ページごとCSSの指定
    <link rel="stylesheet" href="/css/xxx.css">
    --}}
@stop

@section('js')



@stop
