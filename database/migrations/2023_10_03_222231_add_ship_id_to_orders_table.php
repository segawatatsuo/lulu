<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shippingDetailId')->nullable()->comment('発送明細ID');
            $table->string('deliveryCompany')->nullable()->comment('配送会社');
            $table->string('deliveryCompanyName')->nullable()->comment('配送会社名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shippingDetailId');
            $table->dropColumn('deliveryCompany');
            $table->dropColumn('deliveryCompanyName');
        });
    }
}
