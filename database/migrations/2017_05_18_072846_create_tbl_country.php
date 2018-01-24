<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Name of the Country');
            $table->integer('status',false, true)->length(1)->unsigned()->comment('0=Inactive,1=Active');
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
        Schema::drop('tbl_country');
    }
}
