<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTblAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_tbl_answer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('options')->comment('Option of the question');
            $table->integer('question_id')->comment('Instance of tbl_question');
            $table->foreign('question_id')->references('id')->on('tbl_question')->comment('Instance of tbl_question');
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
        Schema::drop('log_tbl_answer');
    }
}
