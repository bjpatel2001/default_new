<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users_device', function (Blueprint $table){
            $table->increments('id');
            $table->integer('app_user_id')->default('0');
            $table->string('device_token',100)->nullable();
            $table->string('notification_id')->nullable();
            $table->tinyInteger('device_type')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('app_users_device');
    }
}
