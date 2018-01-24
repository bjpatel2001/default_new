<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// For Front User Start here
Route::get('/', function () {
    return Redirect::to('admin/login');
});

Route::get('after_reset_password', ['uses' => 'Api\LoginController@afterResetPassword']);
Route::get('/maintanance', function (){
    return view('maintanance');
});



Auth::routes();
Route::group(array('prefix' => '/', 'as' => 'web::'), function() {
    Auth::routes();
    Route::get('app_login', ['uses' => 'AppAuth\LoginController@showLoginForm']);
    Route::post('app_login', ['uses' => 'AppAuth\LoginController@login']);
    Route::post('app_logout', ['uses' => 'AppAuth\LoginController@logout']);
    Route::post('app_password/email', ['uses' => 'AppAuth\ForgotPasswordController@sendResetLinkEmail']);
    //Route::get('app_password/reset/{token?}', ['uses' => 'AppAuth\ForgotPasswordController@sendLinkRequestForm']);
    Route::post('app_password/reset', ['uses' => 'AppAuth\ResetPasswordController@reset']);
    Route::get('app_password/reset/{token}', ['uses' => 'AppAuth\ResetPasswordController@showResetForm']);
    Route::get('user-dashboard', 'Admin\WebDashboardController@index')->name('index');

});

// For Front User Ends here

Route::group(array('prefix' => 'admin', 'as' => 'admin::'), function() {

    Auth::routes();
/*
 *  Dashboard Management
 *  get file from resources/views
 * */

Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'Admin\DashboardController@index']);


/*
 *  Role Management
 *  get files from resources/views/role
 * */

Route::group(array('prefix' => 'role', 'as' => 'role::'), function() {
    Route::any('list', ['as' => 'indexRole', 'uses' => 'Admin\RoleController@index']);
    Route::get('add', ['as' => 'createRole', 'uses' => 'Admin\RoleController@create']);
    Route::get('edit/{id}', ['as' => 'editRole', 'uses' => 'Admin\RoleController@edit']);
    Route::post('store', ['as' => 'storeRole', 'uses' => 'Admin\RoleController@store']);
    Route::post('update', ['as' => 'updateRole', 'uses' => 'Admin\RoleController@update']);
});

/*
 *  Permission Management
 *  get files from resources/views/permission
 * */

Route::group(array('prefix' => 'permission', 'as' => 'permission::'), function() {
    Route::any('list', ['as' => 'indexPermission', 'uses' => 'Admin\PermissionController@index']);
    Route::get('add', ['as' => 'createPermission', 'uses' => 'Admin\PermissionController@create']);
    Route::get('edit/{id}', ['as' => 'editPermission', 'uses' => 'Admin\PermissionController@edit']);
    Route::post('store', ['as' => 'storePermission', 'uses' => 'Admin\PermissionController@store']);
    Route::post('update', ['as' => 'updatePermission', 'uses' => 'Admin\PermissionController@update']);
});

/*
 *  User Management
 *  get files from resources/views/permission
 * */
Route::get('change-password', ['uses' => 'Admin\UserController@changePassword']);
Route::post('update_password', ['uses' => 'Admin\UserController@updatePassword']);
Route::group(array('prefix' => 'user', 'as' => 'user::'), function() {
    Route::any('list', ['as' => 'indexUser', 'uses' => 'Admin\UserController@index']);
    Route::get('add', ['as' => 'createUser', 'uses' => 'Admin\UserController@create']);
    Route::get('profile', ['uses' => 'Admin\UserController@profile']);
    Route::get('edit/{id}', ['as' => 'editUser', 'uses' => 'Admin\UserController@edit']);
    Route::post('delete', ['as' => 'deleteUser', 'uses' => 'Admin\UserController@delete']);
    Route::post('store', ['as' => 'storeUser', 'uses' => 'Admin\UserController@store']);
    Route::post('update', ['as' => 'updateUser', 'uses' => 'Admin\UserController@update']);
    Route::post('change_status', ['uses' => 'Admin\UserController@changeStatus']);
    Route::post('datatable', ['uses' => 'Admin\UserController@datatable']);
});

Route::group(['prefix' => 'app_user'], function () {

    Route::any('/list', 'Admin\AppUserController@index')->name('indexAppUser');
    Route::get('/add', 'Admin\AppUserController@create')->name('createAppUser');
    Route::get('/profile', 'Admin\AppUserController@profile');
    Route::get('/edit/{id}', 'Admin\AppUserController@edit')->name('editAppUser');
    Route::post('/delete', 'Admin\AppUserController@delete')->name('deleteAppUser');
    Route::post('/store', 'Admin\AppUserController@store')->name('storeAppUser');
    Route::post('/update', 'Admin\AppUserController@update')->name('updateAppUser');
    Route::post('/change_status', 'Admin\AppUserController@changeStatus');
    Route::post('/datatable', 'Admin\AppUserController@datatable');
    Route::post('/get_states', ['uses' => 'Admin\AppUserController@getStateList']);
});

Route::group(['prefix' => 'category'], function () {

    Route::any('/list', 'Admin\CategoryController@index')->name('indexCategory');
    Route::get('/add/{flag?}', 'Admin\CategoryController@create')->name('createCategory');
    Route::get('/profile', 'Admin\CategoryController@profile');
    Route::get('/edit/{id}/{flag?}', 'Admin\CategoryController@edit')->name('editCategory');
    Route::get('/log/{id}', 'Admin\CategoryController@log')->name('logCategory');
    Route::post('/delete', 'Admin\CategoryController@delete')->name('deleteCategory');
    Route::post('/store', 'Admin\CategoryController@store')->name('storeCategory');
    Route::post('/update', 'Admin\CategoryController@update')->name('updateCategory');
    Route::post('/change_status', 'Admin\CategoryController@changeStatus');
    Route::post('/datatable', 'Admin\CategoryController@datatable');
    Route::post('/get_product', 'Admin\CategoryController@getMachineByCategoryId');

});
Route::group(['prefix' => 'machine'], function () {

    Route::any('/list', 'Admin\MachineController@index')->name('indexMachine');
    Route::get('/add/{flag?}/{category_id?}', 'Admin\MachineController@create')->name('createMachine');
    Route::get('/profile', 'Admin\MachineController@profile');
    Route::get('/edit/{id}', 'Admin\MachineController@edit')->name('editMachine');
    Route::get('/log/{id}', 'Admin\MachineController@log')->name('logMachine');
    Route::post('/delete', 'Admin\MachineController@delete')->name('deleteMachine');
    Route::post('/store', 'Admin\MachineController@store')->name('storeMachine');
    Route::post('/update', 'Admin\MachineController@update')->name('updateMachine');
    Route::post('/change_status', 'Admin\MachineController@changeStatus');
    Route::post('/change_dashboard_status', 'Admin\MachineController@changeDashboardStatus');
    Route::post('/datatable', 'Admin\MachineController@datatable');
    Route::post('/delete_image', 'Admin\MachineController@deleteImage');
    Route::post('/machine_image', 'Admin\MachineController@machineImage');
});

    Route::group(['prefix' => 'brochure'], function () {

       // Route::any('/list', 'Admin\BrochureController@index')->name('indexBrochure');
        Route::get('/add', 'Admin\BrochureController@create')->name('createBrochure');
        Route::get('/edit/{id}', 'Admin\BrochureController@edit')->name('editBrochure');
        Route::get('/log/{id}', 'Admin\BrochureController@log')->name('logBrochure');
        Route::post('/delete', 'Admin\BrochureController@delete')->name('deleteBrochure');
        Route::post('/store', 'Admin\BrochureController@store')->name('storeBrochure');
        Route::post('/update', 'Admin\BrochureController@update')->name('updateBrochure');
        Route::post('/change_status', 'Admin\BrochureController@changeStatus');
        Route::post('/datatable', 'Admin\BrochureController@datatable');
        Route::get('/get_private_brochure', 'Admin\BrochureController@getPrivateBrochure')->name('privateBrochure');
        Route::post('/delete_file', 'Admin\BrochureController@deleteFile');
    });

Route::group(['prefix' => 'quotation'], function () {

    Route::any('/list', 'Admin\QuotationController@index')->name('indexQuotation');
    Route::get('/edit/{id}', 'Admin\QuotationController@edit')->name('editQuotation');
    Route::post('/store', 'Admin\QuotationController@store')->name('storeQuotation');
    Route::post('/update', 'Admin\QuotationController@update')->name('updateQuotation');
    Route::post('/category_products', 'Admin\QuotationController@categoryProducts')->name('categoryProducts');
});

    Route::group(['prefix' => 'country'], function () {

        // For Country

        Route::any('/list', 'Admin\CountryController@index')->name('indexCountry');
        Route::get('/add', 'Admin\CountryController@create')->name('createCountry');
        Route::get('/edit/{id}', 'Admin\CountryController@edit')->name('editCountry');
        Route::get('/log/{id}', 'Admin\CountryController@log')->name('logCountry');
        Route::post('/delete', 'Admin\CountryController@delete')->name('deleteCountry');
        Route::post('/store', 'Admin\CountryController@store')->name('storeCountry');
        Route::post('/update', 'Admin\CountryController@update')->name('updateCountry');
        Route::post('/change_status', 'Admin\CountryController@changeStatus');
        Route::post('/datatable', 'Admin\CountryController@datatable');

    });

    Route::group(['prefix' => 'state'], function () {

        // For State

        Route::any('/list', 'Admin\StateController@index')->name('indexState');
        Route::get('/add', 'Admin\StateController@create')->name('createState');
        Route::get('/edit/{id}', 'Admin\StateController@edit')->name('editState');
        Route::get('/log/{id}', 'Admin\StateController@log')->name('logState');
        Route::post('/delete', 'Admin\StateController@delete')->name('deleteState');
        Route::post('/store', 'Admin\StateController@store')->name('storeState');
        Route::post('/update', 'Admin\StateController@update')->name('updateState');
        Route::post('/change_status', 'Admin\StateController@changeStatus');
        Route::post('/datatable', 'Admin\StateController@datatable');

    });

    // For News Management

    Route::group(['prefix' => 'news'], function () {

        Route::any('/list', 'Admin\NewsController@index')->name('indexNews');
        Route::get('/add', 'Admin\NewsController@create')->name('createNews');
        Route::get('/edit/{id}', 'Admin\NewsController@edit')->name('editNews');
        Route::get('/log/{id}', 'Admin\NewsController@log')->name('logNews');
        Route::post('/delete', 'Admin\NewsController@delete')->name('deleteNews');
        Route::post('/store', 'Admin\NewsController@store')->name('storeNews');
        Route::post('/update', 'Admin\NewsController@update')->name('updateNews');
        Route::post('/change_status', 'Admin\NewsController@changeStatus');
        Route::post('/datatable', 'Admin\NewsController@datatable');

    });

    // For News Client

    Route::group(['prefix' => 'client'], function () {

        Route::any('/list', 'Admin\ClientController@index')->name('indexClient');
        Route::get('/add', 'Admin\ClientController@create')->name('createClient');
        Route::get('/edit/{id}', 'Admin\ClientController@edit')->name('editClient');
        Route::get('/log/{id}', 'Admin\ClientController@log')->name('logClient');
        Route::post('/delete', 'Admin\ClientController@delete')->name('deleteClient');
        Route::post('/store', 'Admin\ClientController@store')->name('storeClient');
        Route::get('/client-pdf', 'Admin\SettingController@clientPdf')->name('clientPdf');
        Route::post('/store-pdf', 'Admin\SettingController@store')->name('storeSetting');
        Route::post('/update', 'Admin\ClientController@update')->name('updateClient');
        Route::post('/change_status', 'Admin\ClientController@changeStatus');
        Route::post('/datatable', 'Admin\ClientController@datatable');

    });

    // For News Question

    Route::group(['prefix' => 'question'], function () {

        Route::any('/list', 'Admin\QuestionController@index')->name('indexQuestion');
        Route::get('/add', 'Admin\QuestionController@create')->name('createQuestion');
        Route::get('/edit/{id}', 'Admin\QuestionController@edit')->name('editQuestion');
        Route::get('/log/{id}', 'Admin\QuestionController@log')->name('logQuestion');
        Route::post('/delete', 'Admin\QuestionController@delete')->name('deleteQuestion');
        Route::post('/store', 'Admin\QuestionController@store')->name('storeQuestion');
        Route::post('/update', 'Admin\QuestionController@update')->name('updateQuestion');
        Route::post('/change_status', 'Admin\QuestionController@changeStatus');
        Route::post('/datatable', 'Admin\QuestionController@datatable');
        Route::post('/question_option', 'Admin\QuestionController@getQuestionOption');

    });

    // For Request Managment

    Route::group(['prefix' => 'request'], function () {

        Route::any('/list', 'Admin\RequestQuotationController@index')->name('indexRequestQuotationController');
        Route::post('/datatable', 'Admin\RequestQuotationController@datatable');
        Route::get('/view/{id}', 'Admin\RequestQuotationController@view');

    });

});