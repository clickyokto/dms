<?php
Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XInvoice\Controllers'], function() {

        Route::post('invoice/add_invoice_product', 'InvoiceController@addInvoiceProduct');
        Route::get('invoice/get_invoice_products/{invoice_id?}', 'InvoiceController@getInvoiceProducts');
        Route::post('invoice/delete_invoice_product', 'InvoiceController@deleteInvoiceProduct');
        Route::post('invoice/cal_invoice_price', 'InvoiceController@calInvoicePrice');
        Route::get('invoices_list/{filter_id?}/{filter_by?}/{page?}', 'InvoiceController@getInvoiceList')->name('get.invoices');
        Route::get('invoice/get_sales_payments/{filter_id?}/{filter_type?}', 'InvoiceController@getSalesPayments');
        Route::post('invoice/save_payment', 'InvoiceController@saveInvoicePayment');
        Route::post('invoice/delete_payment', 'InvoiceController@deletePayment');
        Route::post('invoice/print_payment', 'InvoiceController@printPayment');

        Route::get('return_from_invoice_list/{customer_id}', 'InvoiceReturnController@getOutstandingInvoiceList');




        Route::post('invoice/generate_pdf', 'InvoiceController@generateInvoicePDF');
        Route::post('invoice/duplicate_invoice', 'InvoiceController@duplicateInvoice');
        Route::post('invoice_return/generate_pdf', 'InvoiceReturnController@generateInvoiceReturnPDF');


        Route::post('get_invoice_status', 'InvoiceController@getInvoiceStatus');



        Route::post('get_invoice_details', 'InvoiceReturnController@getInvoiceDetails');
        Route::post('get_invoice_products_by_category', 'InvoiceReturnController@getProductsByCategory');


        Route::post('invoice_return/cal_invoice_price', 'InvoiceReturnController@calInvoicePrice');
        Route::post('invoice_return/add_invoice_return_product', 'InvoiceReturnController@addInvoiceReturnProduct');
        Route::get('invoice_return/get_invoice_return_products/{invoice_return_id?}', 'InvoiceReturnController@getInvoiceReturnProduct');
        Route::post('invoice_return/delete_invoice_return_product', 'InvoiceReturnController@deleteInvoiceReturnProduct');
        Route::post('invoice_return/get_invoices', 'InvoiceReturnController@getInvoices');
        Route::get('invoice_return_list', 'InvoiceReturnController@getInvoiceReturnList')->name('get.invoice_return');
        Route::post('get_invoice_return_product_details', 'InvoiceReturnController@getInvoiceReturnProductDetails');
        Route::post('invoice_send_email', 'InvoiceController@invoiceSendMail');
        Route::get('invoice_mail/create_mail_model/{invoice_id?}', 'InvoiceController@createMailModel');
        Route::get('customer_invoices/customer_history_modal/{customer_id}', 'InvoiceController@getCustomerHistoryModal');

        Route::get('get_view_used_credit_note_model/{invoice_id}', 'InvoiceController@getCreditNoteHistoryModal');
        Route::get('get_used_credit_note_details_model/{invoice_id}', 'InvoiceController@getCreditNoteHistoryList');

        Route::get('customer_invoice_view/{invoice_id}', 'CustomerInvoiceController@invoiceRequestForPayment');

        Route::get('get_invoice_products_modal', 'InvoiceController@getInvoiceProductsModal');

        Route::post('invoice/search_invoice_prouducts', 'InvoiceController@searchInvoiceProducts');



//        Route::get('save_invoice_billing_address', 'CustomerInvoiceController@saveInvoiceBillingAddress');


        Route::resource('invoice', 'InvoiceController');
        Route::resource('invoice_return', 'InvoiceReturnController');

    });


});


Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XInvoice\Controllers'], function() {

        Route::post('payment_notify', 'CustomerInvoiceController@confirmPayment');


    });
});
