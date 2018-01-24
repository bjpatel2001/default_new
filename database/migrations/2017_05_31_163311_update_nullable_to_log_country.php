<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNullableToLogCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_tbl_country', function (Blueprint $table){
            $table->string('name')->nullable()->change();
            $table->integer('country_id')->after('name');
            $table->integer('status')->nullable()->change();
            $table->integer('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_tbl_country');
    }
}
