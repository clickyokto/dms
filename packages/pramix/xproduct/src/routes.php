<?php
Route::group(['middleware' => ['web', 'auth']], function () {

    Route::group(['namespace' => 'Pramix\XProduct\Controllers'], function () {

        Route::get('get_product_list', 'ProductController@getProductList')->name('get.all_products');

        Route::post('category/get_products_by_category', 'ProductCategoryController@getProductsByCategory');
        Route::post('get_products_by_category', 'ProductController@getProductsByCategory');
        Route::post('get_product_details', 'ProductController@getProductDetails');
        Route::post('get_product_details_by_barcode', 'ProductController@getProductDetailsByBarcode');

        Route::get('get_manufactures_list', 'ProductController@getManufactureList');

        Route::get('product/product_movement_history/{product_id}', 'ProductController@productMovementHistory');
        Route::post('product/picture_upload', 'ProductController@uploadItemPicture');
        Route::post('delete_product_image', 'ProductController@deleteProductMedia');

        Route::resource('product', 'ProductController');
        Route::resource('category', 'ProductCategoryController');

    });

});
