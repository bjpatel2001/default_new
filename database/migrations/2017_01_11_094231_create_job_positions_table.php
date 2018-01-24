<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create job positions master table
        Schema::create('tbl_job_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_title')->nullable()->comment('Job Title!');
            $table->integer('client_id')->comment('tbl_client id reference');
            $table->foreign('client_id')->references('id')->on('tbl_client');
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
        Schema::drop('tbl_job_positions');
    }
}
