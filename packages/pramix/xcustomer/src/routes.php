<?php

Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XCustomer\Controllers'], function() {


        Route::get('get_customer_list', 'CustomerController@getCustomerList')->name('get.all_customers');
        Route::post('customer/get_customer_details', 'CustomerController@getCustomerDetails');

        Route::get('customer/customer_history_modal', 'CustomerController@getCustomerHistoryModal');


        Route::get('get_select_two_customer_name_filter', 'CustomerController@getSelectTwoCustomerNameFilter');
        Route::get('get_select_two_customer_code_filter', 'CustomerController@getSelectTwoCustomerCodeFilter');

        Route::post('get_customer_balance_details', 'CustomerController@getCustomerBalanceDetails');



        Route::resource('customer', 'CustomerController');

    });


});
