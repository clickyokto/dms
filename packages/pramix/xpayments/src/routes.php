<?php
Route::group(['middleware' => ['web', 'auth']], function () {

    Route::group(['namespace' => 'Pramix\XPayment\Controllers'], function () {

        Route::get('get_payment_list', 'PaymentController@getPaymentList')->name('get.all_payments');


        Route::get('payment/view/{id?}' , 'PaymentController@viewPayment');
        Route::post('payment/cal_payment_price', 'PaymentController@calPaymentePrice');

        Route::post('payment/save_payment', 'PaymentController@saveInvoicePayment');
        Route::post('payment/reject_payment', 'PaymentController@rejectPayment');
        Route::post('payment/approve_payment', 'PaymentController@approvePayment');


        Route::post('payment_edit' , 'PaymentController@PaymentEdit');

        Route::get('cheque_payment' , 'PaymentController@viewChequePayments');

        Route::get('get_cheque_payments_list', 'PaymentController@getChequePaymentsList')->name('get.all_cheque_payments');


        Route::get('get_auto_data', 'ChequeController@getAutoLoadData');

        Route::get('get_cheque_list', 'ChequeController@getChequesList')->name('get.all_cheques');
        Route::post('cheque/cheque_print', 'ChequeController@chequePrint');

        Route::resource('payment', 'PaymentController');
        Route::resource('cheque', 'ChequeController');
    });

});
