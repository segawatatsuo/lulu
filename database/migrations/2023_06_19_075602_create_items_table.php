<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('users');//外部キー

            $table->string('sku')->comment('ユニークな商品番号');
            $table->string('product_code')->nullable()->comment('重複もありえる商品番号');
            $table->string('product_name')->nullable()->comment('商品名');
            $table->string('quantity')->nullable()->comment('数量');
            $table->string('price')->nullable()->comment('単価');
            $table->string('stock')->nullable()->comment('単価');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
