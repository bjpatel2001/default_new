<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		 // Create client master table
         Schema::create('tbl_client', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_name')->nullable()->comment('Client name!');
            $table->integer('country_id')->nullable()->comment('Country code of client');
            $table->foreign('country_id')->references('id')->on('tbl_country');
			$table->integer('industry_id')->nullable()->comment('Client industry type!');
            $table->foreign('industry_id')->references('id')->on('tbl_industry');
			$table->string('client_code',30)->nullable()->comment('Client industry type!');
            $table->integer('status')->unsigned()->nullable()->comment('0=Inactive,1=Active');
            $table->integer('created_by')->unsigned()->comment('User id who create the user!');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->unsigned()->comment('User id who update the user!');
            $table->foreign('updated_by')->references('id')->on('users');
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
         Schema::drop('tbl_client_master');
    }
}
