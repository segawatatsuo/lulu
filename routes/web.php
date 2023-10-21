<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SearchOrderController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Models\Product;
use Database\Seeders\SearchOrderSeeder;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#
Route::get('/', function () {
    return view('index');
});

Auth::routes();

#ダッシュボード
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('adminlte', function () {
    return view('adminlte');
});


#受注状況
Route::get('order',[OrderController::class,'index'])->name('order.index');
Route::get('order.show/{id}',[OrderController::class,'show'])->name('order.show');

#出荷状況
Route::get('shipping',[OrderController::class,'shipping'])->name('shipping.index');

#入荷
Route::get('arrival',[ArrivalController::class,'index'])->name('arrival.index');
Route::get('arrival.create',[ArrivalController::class,'create'])->name('arrival.create');
Route::get('arrival.show/{id}',[ArrivalController::class,'show'])->name('arrival.show');    // バリデーションの戻り表示
Route::post('arrival.show/{id}',[ArrivalController::class,'show'])->name('arrival.show');    // 表示
Route::post('arrival.store',[ArrivalController::class,'store'])->name('arrival.store');
Route::get('arrival.store',[ArrivalController::class,'store'])->name('arrival.store');
Route::post('arrival.destroy/{id}/',[ArrivalController::class,'destroy'])->name('arrival.destroy');  // 削除


#商品情報
Route::get('item',[ItemController::class,'index'])->name('item.index');
Route::get('item.create',[ItemController::class,'create'])->name('item.create');
Route::post('item.store',[ItemController::class,'store'])->name('item.store');


Route::get('product',[ProductController::class,'index'])->name('product.index');
Route::get('product.show/{id}',[ProductController::class,'show'])->name('product.show');
Route::post('product.show/{id}',[ProductController::class,'show'])->name('product.show');
Route::post('product.destroy/{id}/',[ProductController::class,'destroy'])->name('product.destroy'); 
Route::post('product.update/{id}/',[ProductController::class,'update'])->name('product.update');  // 完了


Route::post('item.show/{id}',[ItemController::class,'show'])->name('item.show');    // 表示
Route::get('item.show/{id}',[ItemController::class,'show'])->name('item.show');    // バリデートの戻り表示用

Route::post('item.update/{id}/',[ItemController::class,'update'])->name('item.update');  // 完了
Route::post('item.destroy/{id}/',[ItemController::class,'destroy'])->name('item.destroy');  // 削除

#SearchOrder
Route::get('search-order',[SearchOrderController::class,'index'])->name('search-order.index');
Route::get('getorder',[SearchOrderController::class,'getorder']);
Route::get('rakuten_update',[SearchOrderController::class,'rakuten_update']);

#楽天にアップロード
Route::get('upload',[OrderController::class,'upload'])->name('upload.index');
Route::post('store',[OrderController::class,'store'])->name('upload.store');


Route::get('api_upload',[SearchOrderController::class,'api_upload']);

#テスト
Route::get('test', [SearchOrderController::class,'test']);

#上部検索フォーム
Route::get('search',[SearchController::class,'index']);