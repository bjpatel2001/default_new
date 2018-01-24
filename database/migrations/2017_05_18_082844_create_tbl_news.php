<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('title of the news');
            $table->string('image')->comment('cover of image');
            $table->longText('description')->comment('News content');
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
        Schema::drop('tbl_news');
    }
}
