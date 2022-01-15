<?php

Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XMedia\Controllers'], function() {
        Route::post('media_upload', 'MediaController@uploadMedia');
        Route::post('delete_media', 'MediaController@deleteMedia');
        Route::post('update_media_order', 'MediaController@updateMediaOrder');


    });
});