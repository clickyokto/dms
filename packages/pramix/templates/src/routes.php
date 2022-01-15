<?php

Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\Templates'], function() {
        Route::resource('invoice_templates', 'Controllers\InvoiceTemplatesController');

        Route::post('invoice_templates/get_shortcodes_by_type', 'InvoiceTemplatesController@getShortcodesByType');
        Route::get('get_invoice_templates', 'Controllers\InvoiceTemplatesController@getInvoiceTemplates')->name('get.invoice_templates');
    });
});