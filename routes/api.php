<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(array('prefix' => 'v1'), function() {
    Route::post('login', ['uses' => 'Api\LoginController@login']);
    Route::post('signup', ['uses' => 'Api\LoginController@signUp']);
    Route::post('forgot_password', ['uses' => 'Api\LoginController@sendResetLinkEmail']);
    // Country List with All State
    Route::get('country_list', ['uses' => 'Api\CountryController@countryList']);
    Route::group(array('middleware' => ['auth:api','checkStatus']), function() {
        Route::post('update_password', ['uses' => 'Api\LoginController@updatePassword']);
        Route::post('logout', ['uses' => 'Api\LoginController@logout']);
        Route::post('get_user_profile', ['uses' => 'Api\LoginController@getUserProfileById']);
        Route::post('update_profile', ['uses' => 'Api\LoginController@updateProfile']);
        // For Getting Brochure List
        Route::post('brochure_list', ['uses' => 'Api\BrochureController@getBrochureList']);
        // Machine List for Dashboard
        Route::post('machine_list', ['uses' => 'Api\MachineController@getDashboardMachineList']);
        // Cateogry List
        Route::post('category_list', ['uses' => 'Api\MachineController@getCategoryWithMachine']);
        // Machine lisy category wise
        Route::post('machine_listing', ['uses' => 'Api\MachineController@getMachineListing']);
        // Machine details
        Route::post('machine_detail', ['uses' => 'Api\MachineController@getMachineDetail']);
        // News List
        Route::post('news_list', ['uses' => 'Api\NewsController@getNewsList']);
        // Client List
        Route::post('client_list', ['uses' => 'Api\ClientController@getClientList']);
        // Quotation List
        Route::post('quotation_list', ['uses' => 'Api\QuotationController@getQuotationData']);
        //Question and Location List
        Route::post('question_list', ['uses' => 'Api\QuestionController@getQuestionList']);
        // For submitting the Qoutation from App
        Route::post('request_quotation', ['uses' => 'Api\RequestQuotationController@store']);
        // For login of facebook

        Route::get('auth/facebook', 'Api\FacebookController@redirectToFacebook');
        Route::get('auth/facebook/callback', 'Api\FacebookController@handleFacebookCallback');

         // Web User
        // For Getting Brochure List For Web User
        Route::get('web_master_brochure',['uses' => 'Api\BrochureController@getMasterBrochureForUser']);
        // For Web User Login
        Route::post('web_user_login',['uses' => 'Api\LoginController@web_login']);


    });

});
