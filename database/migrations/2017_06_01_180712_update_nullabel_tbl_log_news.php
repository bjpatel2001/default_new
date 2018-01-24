<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNullabelTblLogNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_tbl_news', function (Blueprint $table){
            $table->string('title')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->integer('news_id')->after('title');
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
        Schema::drop('log_tbl_news  ');
    }
}
