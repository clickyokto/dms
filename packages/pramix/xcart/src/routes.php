<?php


Route::group(['middleware' => ['web', 'auth']], function () {

    Route::group(['namespace' => 'Pramix\XCart\Controllers'], function () {
        Route::post('add_to_cart', 'CartController@addToCart');

        Route::post('clear_cart', 'CartController@clearCart');
        Route::post('create_invoice_from_cart', 'CartController@createInvoiceFromCart');

        Route::get('get_cart_products', 'ShopController@getCartProducts');




//        Route::get('cart', 'CartController@index');

        Route::get('get_add_to_rent_modal', 'CartController@getAddToRentModal');
        Route::post('cart/item_remove_from_cart', 'CartController@removeFromCart');
        Route::post('create_invoice_by_cart', 'CartController@createInvoiceByCart');

        Route::get('get_added_product_list', 'CartController@getAddedProductList')->name('get.get_added_product_list');


//        Route::get('product_model/{product_id?}', 'ShopController@getAddToRentModal');

        Route::resource('cart', 'CartController');
        Route::resource('shop', 'ShopController');


    });

});

