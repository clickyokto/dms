<?php
Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XPurchaseOrder\Controllers'], function() {

        Route::post('purchase_order/add_purchase_order_product', 'PurchaseOrderController@addPurchaseOrderProduct');
        Route::get('purchase_order/get_purchase_order_products/{purchase_order_id?}', 'PurchaseOrderController@getPurchaseOrderProducts');
        Route::post('purchase_order/delete_purchase_order_product', 'PurchaseOrderController@deletePurchaseOrderProduct');
        Route::post('purchase_order/cal_purchase_order_price', 'PurchaseOrderController@calPurchaseOrderPrice');
        Route::get('purchase_orders_list/{filter_id?}/{filter_by?}/{page?}', 'PurchaseOrderController@getPurchaseOrderList')->name('get.purchase_orders');
        Route::get('purchase_order/get_sales_payments/{filter_id?}/{filter_type?}', 'PurchaseOrderController@getSalesPayments');
        Route::post('purchase_order/save_payment', 'PurchaseOrderController@savePurchaseOrderPayment');
        Route::post('purchase_order/delete_payment', 'PurchaseOrderController@deletePayment');
        Route::post('purchase_order/print_payment', 'PurchaseOrderController@printPayment');
        Route::post('purchase_order/generate_pdf', 'PurchaseOrderController@generatePurchaseOrderPDF');
        Route::post('purchase_order/duplicate_purchase_order', 'PurchaseOrderController@duplicatePurchaseOrder');
        Route::post('purchase_order_send_email', 'PurchaseOrderController@purchase_orderSendMail');
        Route::get('purchase_order_mail/create_mail_model/{purchase_order_id?}', 'PurchaseOrderController@createMailModel');
        Route::get('get_view_used_grn_model/{purchase_order_id}', 'PurchaseOrderController@getGRNHistoryModal');
        Route::get('get_used_grn_details_model/{purchase_order_id}', 'PurchaseOrderController@getGRNHistoryList');



        Route::get('get_bill_list', 'BillController@getPaymentList')->name('get.all_bills');
        Route::get('bill/view/{id?}' , 'BillController@viewPayment');
        Route::post('bill/cal_bill_price', 'BillController@calPaymentePrice');

        Route::post('bill/save_bill', 'BillController@savePurchaseOrderPayment');



        Route::get('get_bill_list', 'POBillController@getPaymentList')->name('get.all_po_bills');


        Route::resource('purchase_order', 'PurchaseOrderController');
        Route::resource('bill', 'BillController');


    });


});


Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XPurchaseOrder\Controllers'], function() {

        Route::post('bill_notify', 'CustomerPurchaseOrderController@confirmPayment');


    });
});
