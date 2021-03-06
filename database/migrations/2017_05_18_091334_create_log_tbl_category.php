<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTblCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_tbl_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name');
            $table->integer('status',false, true)->length(1)->unsigned()->comment('0=Inactive,1=Active');
            $table->string('action');
            $table->integer('created_by')->comment('User id who create the user!');
            $table->integer('updated_by')->comment('User id who update the user!')->nullable();
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
        //
        Schema::drop('log_tbl_category');
    }
}
