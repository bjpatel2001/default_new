<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMachineImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_machine_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->integer('machine_id');
            $table->foreign('machine_id')->references('id')->on('tbl_machine')->comment('Instance of tbl_machine');
            $table->integer('created_by')->comment('User id who create the images!');
            $table->integer('updated_by')->comment('User id who update the images!')->nullable();
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
        Schema::drop('tbl_machine_images');
    }
}
