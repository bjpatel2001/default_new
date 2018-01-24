<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblNotification extends Migration
{
    public function up()
    {
        Schema::create('tbl_notifications', function (Blueprint $table){
            $table->increments('id');
            $table->string('type');
            $table->tinyInteger('notification_id');
            $table->tinyInteger('receiver_id');
            $table->tinyInteger('sender_id');
            $table->text('message');
            $table->tinyInteger('is_read')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_notifications');
    }
}
