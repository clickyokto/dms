<?php
Route::group(['middleware' => ['web', 'auth']], function () {

    Route::group(['namespace' => 'Pramix\XGeneral\Controllers'], function () {
        Route::post('get_cities_list_by_district_id', 'AddressController@getCitiesByDistrict');
        Route::post('get_district_by_city_id', 'AddressController@getDistrictByCity');

        Route::post('get_general_category_by_finance_category', 'CategoryController@getGeneralCategoryByFinanceCategory');
        Route::post('get_finance_category_by_general_category', 'CategoryController@getFinanceCategoryByGeneralCategory');


        Route::get('get_comments_list/{ref_type?}/{ref_id?}', 'CommentsController@getCommentsList');

        Route::get('get_areas_list', 'AreaController@getAreasList');



        Route::get('settings', 'SettingsController@settingsPage');


        Route::post('save_comment', 'CommentsController@saveComment')->name('post.all_comments');

        Route::post('settings/store', 'SettingsController@store');

        Route::post('settings/logo_upload', 'SettingsController@logoUpload');
        Route::post('data_import/download_excel', 'DataImportController@downloadExcel');



        Route::resource('data_import', 'DataImportController');
        Route::resource('areas', 'AreaController');

    });

});
