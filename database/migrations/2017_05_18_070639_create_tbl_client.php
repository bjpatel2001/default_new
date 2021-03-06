<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_client', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Name of the client');
            $table->string('image')->comment('Single image of the client');
            $table->longText('description')->comment('Description of the client company');
            $table->integer('type',false, true)->length(1)->unsigned()->comment('0=Private,1=Co-operative Dairy');
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
        Schema::drop('tbl_client');
    }
}
