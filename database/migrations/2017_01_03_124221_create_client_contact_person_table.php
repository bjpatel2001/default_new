<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientContactPersonTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		 // Create client contact person table
         Schema::create('tbl_client_contact_person', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
         	$table->foreign('client_id')->references('id')->on('tbl_client')->onDelete('cascade');
			$table->string('first_name')->nullable()->comment('Contact person first name!');
			$table->string('last_name')->nullable()->comment('Contact person last name!');
			$table->string('email')->unique()->comment('Contact person email address!');
			$table->string('landline_number',15)->nullable()->comment('Contact person landline number!');
			$table->string('mobile_number',15)->nullable()->comment('Contact person mobile number!');
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
         Schema::drop('tbl_client_contact_person');
    }
}
