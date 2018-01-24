<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create country table
        Schema::create('tbl_country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',60)->comment('Country name!');
            $table->string('code',10)->unique()->comment('Country short code!');
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
        Schema::drop('tbl_country');
    }
}
