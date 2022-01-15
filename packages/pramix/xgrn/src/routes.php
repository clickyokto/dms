<?php
Route::group(['middleware' => ['web', 'auth']], function () {

    Route::group(['namespace' => 'Pramix\XGRN\Controllers'], function () {


        Route::post('grn_return/generate_pdf', 'GRNReturnController@generateGRNReturnPDF');



        Route::post('grn_return/get_grns', 'GRNReturnController@getGRN');
        Route::post('grn_return/cal_order_price', 'GRNReturnController@calOrderPrice');
        Route::post('get_grn_details', 'GRNReturnController@getGRNDetails');
        Route::post('get_po_details', 'GRNController@getPODetails');
        Route::post('grn_return/add_grn_return_product', 'GRNReturnController@addGRNReturnProduct');
        Route::get('grn_return/get_grn_return_products/{grn_return_id?}', 'GRNReturnController@getGRNReturnProduct');
        Route::post('grn_return/delete_grn_return_product', 'GRNReturnController@deleteGRNReturnProduct');
        Route::get('grn_return_list', 'GRNReturnController@getGRNReturnList')->name('get.grn_return');
        Route::post('get_grn_return_product_details', 'GRNReturnController@getGRNReturnProductDetails');


        Route::post('get_grn_productget_used_grn_details_models_by_category', 'GRNReturnController@getProductsByCategory');


        Route::get('grn_list', 'GRNController@getGRNList')->name('get.grn');
        Route::get('grn/get_grn_products/{order_id?}', 'GRNController@getGRNProducts');
        Route::post('grn/add_grn_product', 'GRNController@addGRNProduct');
        Route::post('grn/delete_grn_product', 'GRNController@deleteGRNProduct');
        Route::post('grn/generate_pdf', 'GRNController@generateGRNPDF');
        Route::post('grn/cal_order_price', 'GRNController@calOrderPrice');
        Route::post('grn/approve_grn', 'GRNController@approveGRN');



//        Route::post('get_grn_product_details', 'GRNController@getGRNProductDetails');




        Route::resource('grn', 'GRNController');
        Route::resource('grn_return', 'GRNReturnController');

    });


});
