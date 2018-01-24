<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNullableToBrochureLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_tbl_brochure', function (Blueprint $table){
            $table->integer('category_id')->nullable()->change();
            $table->integer('machine_id')->nullable()->change();
            $table->integer('type')->nullable()->change();
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
        Schema::drop('log_tbl_brochure');
    }
}
