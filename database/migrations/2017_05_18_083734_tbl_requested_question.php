<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblRequestedQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_requested_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->comment('Instance of tbl_request_quotation');
            $table->foreign('request_id')->references('id')->on('tbl_request_quotation')->comment('Instance of tbl_request_quotation');
            $table->integer('question_id')->comment('Instance of tbl_question');
            $table->foreign('question_id')->references('id')->on('tbl_question')->comment('Instance of tbl_question');
            $table->integer('answer_id')->comment('Instance of tbl_answer');
            $table->foreign('answer_id')->references('id')->on('tbl_answer')->comment('Instance of tbl_answer');
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
        Schema::drop('tbl_requested_question');
    }
}
