<?php
Route::group(['middleware' => ['web', 'auth']], function() {

    Route::group(['namespace' => 'Pramix\XReports\Controllers'], function() {

        Route::get('reports/index', 'ReportsController@index')->name('reports.index');


        Route::get('reports/inventory_movement_summary_report', 'ReportsController@inventoryMovementSummaryReport');
        Route::get('reports/products_report', 'ReportsController@productsReport');
        Route::get('reports/sales_by_product_summary_report', 'ReportsController@salesByProductSummary');
        Route::get('reports/sales_order_summary_report', 'ReportsController@salesOrderSummary');
        Route::get('reports/payment_summary_report', 'ReportsController@paymentSummaryReport');
        Route::get('reports/billing_summary_report', 'ReportsController@billingSummaryReport');
        Route::get('reports/outstanding_report', 'ReportsController@customerOutstandingReport');
        Route::get('reports/outstanding_report_period_wise', 'ReportsController@customerOutstandingReportPeriodWise');
        Route::get('reports/cheque_return_outstanding_report', 'ReportsController@chequeReturnOutstandingReport');
        Route::get('reports/low_stock_products_report', 'ReportsController@lowStockProductsReport');



        Route::get('reports/purchasing_summary_report', 'ReportsController@purchasingSummaryReport');

        Route::post('reports/generate_daily_collection_report', 'ReportsController@generateInventoryMovementSummaryReport');

        Route::post('reports/generate_products_report', 'ReportsController@generateProductsReport');
        Route::post('reports/generate_sales_by_product_summary_report', 'ReportsController@generateSalesByProductSummaryReport');
        Route::post('reports/generate_sales_order_summary_report', 'ReportsController@generateSalesOrderSummaryReport');
        Route::post('reports/generate_purchase_order_summary_report', 'ReportsController@generatePurchaseOrderSummaryReport');
//        Route::post('reports/generate_customer_outstanding_report', 'ReportsController@generateCustomerOutstandingReport');
        Route::post('reports/generate_payment_summary_report', 'ReportsController@generatePaymentSummaryReport');
        Route::post('reports/generate_bill_summary_report', 'ReportsController@generateBillingSummaryReport');
        Route::post('reports/generate_customer_outstanding_report', 'ReportsController@generateCustomerOutstandingReport');
        Route::post('reports/generate_customer_outstanding_period_wise_report', 'ReportsController@generateCustomerOutstandingPeriodWiseReport');
        Route::post('reports/generate_cheque_return_outstanding_report', 'ReportsController@generateChequeReturnOutstandingReport');
        Route::post('reports/generate_low_stock_products_report', 'ReportsController@generateLowStockProductsReport');





    });


});
