<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->comment('会社名');
            $table->string('postal_code')->nullable()->comment('郵便番号');
            $table->string('addr01')->nullable()->comment('住所1');
            $table->string('addr02')->nullable()->comment('住所2');
            $table->string('phone_number')->nullable()->comment('電話番号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_name');  //カラムの削除
            $table->dropColumn('postal_code');
            $table->dropColumn('addr01');
            $table->dropColumn('addr02');
            $table->dropColumn('phone_number');
        });
    }
}
