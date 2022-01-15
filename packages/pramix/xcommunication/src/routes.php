<?php

Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XCommunication'], function() {

        Route::get('templates/{type}', 'Controllers\TemplateController@index');
        Route::get('gettemplatelist/{type}', 'Controllers\TemplateController@getTemplatesList');
        Route::get('templates/create/{type}', 'Controllers\TemplateController@create');

        Route::get('templates/{type}/{id}/edit', 'Controllers\TemplateController@edit');

        Route::get('listallsendsms', 'Controllers\SMSController@listAllSendSMS');
        Route::get('listallsendemails', 'Controllers\EmailController@listAllSendEmails');
        Route::get('emails/preview_sent_email/{sent_email_id}', 'Controllers\EmailController@previewSentEmail');


        //Email Editor

        Route::get('email/email_editor/{template_id?}', 'Controllers\EmailController@getEmailEditor');
        Route::get('email/elements/{file}', function($file) {
            return \file_get_contents(asset('emarketing/elements/' . $file));
        });

        Route::post('emails/upload_image', 'Controllers\EmailController@uploadMedia');
        Route::get('emails/get_images', 'Controllers\EmailController@getMedia');
        Route::post('emails/get_customers_by_type', 'Controllers\EmailController@getCustomersByRadioType');
        Route::post('sms/get_customers_list', 'Controllers\SMSController@getCustomerList');
        //End Email Editor



        Route::resource('templates', 'Controllers\TemplateController');
        Route::resource('sendsms', 'Controllers\SMSController');
        Route::resource('emails', 'Controllers\EmailController');
    });
});

Route::group(['middleware' => ['web']], function() {
    Route::group(['namespace' => 'Pramix\XCommunication'], function() {
        Route::get('email/assets/images/social-icons/{file}', function($file) {
            return \file_get_contents(asset('emarketing/assets/images/social-icons/' . $file));
        });
        Route::get('email/assets/images/service-list/{file}', function($file) {
            return \file_get_contents(asset('emarketing/assets/images/service-list/' . $file));
        });
    });
});
