<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMachine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_machine', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_name');
            $table->integer('category_id');
            $table->foreign('category_id')->references('id')->on('tbl_category')->comment('Instance of tbl_category');
            $table->longText('description');
            $table->integer('status',false, true)->length(1)->unsigned()->comment('0=Inactive,1=Active');
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
        Schema::drop('tbl_machine');
    }
}
