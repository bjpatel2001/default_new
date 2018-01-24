<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTblQuotationCategoryMachine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_tbl_quotation_category_machine', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->comment('Instance of tbl_quotation');
            $table->foreign('quotation_id')->references('id')->on('tbl_quotation')->comment('Instance of tbl_quotation');
            $table->integer('category_id')->comment('Instance of tbl_category');
            $table->foreign('category_id')->references('id')->on('tbl_category')->comment('Instance of tbl_category');
            $table->integer('machine_id')->comment('Instance of tbl_machine');
            $table->foreign('machine_id')->references('id')->on('tbl_machine')->comment('Instance of tbl_machine');
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
        Schema::drop('log_tbl_quotation_category_machine');
    }
}
