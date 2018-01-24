<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTblCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_tbl_country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Name of the Country');
            $table->integer('status',false, true)->length(1)->unsigned()->comment('0=Inactive,1=Active');
            $table->string('action');
            $table->integer('created_by')->comment('User id who created record !');
            $table->integer('updated_by')->comment('User id who update record!')->nullable();
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
        Schema::drop('log_tbl_country');
    }
}
