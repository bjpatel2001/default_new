<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTblRequestQuotation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_tbl_request_quotation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('User id from the logined app user');
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
        Schema::drop('log_tbl_request_quotation');
    }
}
