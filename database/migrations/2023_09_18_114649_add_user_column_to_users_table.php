<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->comment('ユーザー権限');
            $table->string('rms_service_secret')->comment('楽天APIシークレット');
            $table->string('rms_license_key')->comment('楽天APIライセンスキー');
            $table->string('rms_mail_auth')->comment('楽天メールAUTH');
            $table->string('rms_mail_path')->comment('楽天メールパスワード');
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
            $table->dropColumn('role');
            $table->dropColumn('rms_service_secret');
            $table->dropColumn('rms_license_key');
            $table->dropColumn('rms_mail_auth');
            $table->dropColumn('rms_mail_path');
        });
    }
}
