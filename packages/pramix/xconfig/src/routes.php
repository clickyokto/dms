<?php
Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XConfig\Controllers'], function() {


//
        Route::get('get_configs_list', 'XConfigController@getConfigsList')->name('get.all_configs');

        Route::get('get_config_categories_list', 'ConfigCategoriesController@getConfigCategoriesList')->name('get.all_config_categories');

        Route::resource('configurations', 'XConfigController');
        Route::resource('config_categories', 'ConfigCategoriesController');


    });


});
