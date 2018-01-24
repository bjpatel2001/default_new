<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       // Add new fieles to users table
	   Schema::table('users', function (Blueprint $table) {
	   		$table->string('employee_code')->after('id')->nullable()->comment('Employee code!');
       		$table->string('first_name')->after('name')->nullable()->comment('User first name!');
			$table->string('last_name')->after('first_name')->nullable()->comment('User last name!');
			$table->timestamp('joining_date')->after('last_name')->nullable()->comment('User joining date!');
            $table->integer('role_id')->unsigned()->after('email');
            $table->foreign('role_id')->references('id')->on('tbl_user_role')->comment('User role!');
			$table->string('landline_number',15)->after('role_id')->nullable()->comment('User landline number!');
			$table->string('mobile_number',15)->after('landline_number')->nullable()->comment('User mobile number!');
			$table->integer('status',false, true)->length(1)->unsigned()->after('password')->nullable()->comment('0=Inactive,1=Active');
            $table->integer('created_by')->unsigned()->after('status');
            $table->foreign('created_by')->references('id')->on('users')->comment('User id who create the user!');
            $table->integer('updated_by')->unsigned()->after('created_by');
            $table->foreign('updated_by')->references('id')->on('users')->comment('User id who update the user!');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('users');
    }
}
