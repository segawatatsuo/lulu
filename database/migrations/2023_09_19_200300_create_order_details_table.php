<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('orderNumber')->comment('注文番号');//リレーション用
            $table->integer('itemDetailId')->nullable()->comment('商品明細ID');
            $table->string('itemName')->nullable()->comment('商品名');
            $table->integer('itemId')->nullable()->comment('商品ID');
            $table->string('itemNumber')->nullable()->comment('商品番号');
            $table->string('manageNumber')->nullable()->comment('商品管理番号');
            $table->integer('price')->nullable()->comment('単価');
            $table->integer('units')->nullable()->comment('個数');
            $table->integer('includePostageFlag')->nullable()->comment('送料込別');
            $table->integer('includeTaxFlag')->nullable()->comment('税込別');
            $table->integer('includeCashOnDeliveryPostageFlag')->nullable()->comment('代引手数料込別');
            $table->string('selectedChoice')->nullable()->comment('項目・選択肢');
            $table->integer('pointRate')->nullable()->comment('ポイント倍率');
            $table->integer('pointType')->nullable()->comment('ポイントタイプ');
            $table->integer('inventoryType')->nullable()->comment('在庫タイプ');
            $table->string('delvdateInfo')->nullable()->comment('納期情報');
            $table->integer('restoreInventoryFlag')->nullable()->comment('在庫連動オプション');
            $table->integer('dealFlag')->nullable()->comment('楽天スーパーDEAL商品フラグ');
            $table->integer('drugFlag')->nullable()->comment('医薬品フラグ');
            $table->integer('deleteItemFlag')->nullable()->comment('商品削除フラグ');
            $table->integer('taxRate')->nullable()->comment('商品税率');
            $table->integer('priceTaxIncl')->nullable()->comment('商品毎税込価格');
            $table->integer('isSingleItemShipping')->nullable()->comment('単品配送フラグ');
            $table->string('SkuModelList_skuInfo')->nullable()->comment('SKUモデルリスト');
            $table->string('SkuModelList_variantId')->nullable()->comment('');
            $table->string('SkuModelList_merchantDefinedSkuId')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
