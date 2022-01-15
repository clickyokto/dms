<?php

//Route::get('users', 'Pramix\XUser\XUserController@index');
//Route::get('getprocedurepricelist', 'ProcedureController@getProcedurePriceList')->name('get.procedure_price_list');

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::group(['namespace' => 'Pramix\XUser'], function () {

        Route::resource('users', 'Controllers\XUserController');

        Route::resource('roles', 'Controllers\RoleController');

        Route::resource('permissions', 'Controllers\PermissionController');

        //Route::resource('accounting/accounts', 'Controllers\AccountsController');     
        //Route::get('accounting/companyget_companies', 'Controllers\CompanyController@getCompaniesList')->name('get.companies_list');     

        Route::get('userslist', 'Controllers\XUserController@getUserList')->name('get.users');

        Route::get('change_password', 'Controllers\XUserController@changePasswordView');

        Route::post('change_password', 'Controllers\XUserController@updatePassword');


        Route::get('user_roles_list', 'Controllers\RoleController@getRoles')->name('get.user_roles');

        Route::post('user/change_status', 'Controllers\XUserController@userChangeStatus');

        Route::post('change_user_theme', 'Controllers\XUserController@changeUserTheme');


    });
});


?>
