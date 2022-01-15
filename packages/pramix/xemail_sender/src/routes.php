<?php
Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XEmailSender\Controllers'], function() {

        Route::get('get_emails_list', 'EmailSenderController@getEmailsList')->name('get.all_emails');

        Route::resource('email_sender', 'EmailSenderController');
    });
});
