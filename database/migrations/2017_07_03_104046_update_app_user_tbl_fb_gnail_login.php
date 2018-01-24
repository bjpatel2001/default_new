<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAppUserTblFbGnailLogin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_users', function (Blueprint $table){
            $table->string('social_login_id')->after('api_token')->comment('Social unique login id')->nullable();
            $table->integer('login_type')->after('social_login_id')->comment('1 For NormalLogin 2 For Facebook 3 For Gmail')->nullable();
            $table->longText('api_response')->after('login_type')->comment('JSON response of API')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('app_users');
    }
}
