<?php

namespace Pramix\XReports\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Pramix\XCustomer\Models\CustomerModel;
use PDF;
use Pramix\XProduct\Models\ProductsModel;

class ReportsModel extends Model
{
    public static function getReportTimeRange($filter_details) {
        $invoice_date_range = $filter_details['date_range'];
        $sales_order_from_date = $filter_details['date_from'];
        $sales_order_end_date = $filter_details['date_to'];

        $date_range = array();

        switch ($invoice_date_range) {
            case "today":
                $date_range['from_date'] = Carbon::now()->startOfDay();
                $date_range['end_date'] = Carbon::now();
                break;
            case "this_month":
                $date_range['from_date'] = Carbon::now()->startOfMonth();
                $date_range['end_date'] = Carbon::now();
                break;
            case "this_quarter":
                $now = Carbon::now();
                $date_range['from_date'] = $now->startOfQuarter();
                $date_range['end_date'] = Carbon::now();
                break;
            case "this_year":
                $date_range['from_date'] = Carbon::now()->startOfYear();
                $date_range['end_date'] = Carbon::now();
                break;
            case "last_7_days":
                $date_range['from_date'] = Carbon::now()->subWeek();
                $date_range['end_date'] = Carbon::now();
                break;
            case "last_30_days":
                $date_range['from_date'] = Carbon::now()->subMonth();
                $date_range['end_date'] = Carbon::now();
                break;
            case "custom_date_range":
                $date_range['from_date'] = Carbon::createFromFormat('Y-m-d',$sales_order_from_date)->startOfDay();
                $date_range['end_date'] = Carbon::createFromFormat('Y-m-d',$sales_order_end_date);
                break;
        }

        return $date_range;
    }


    public static function generateChequeReturnReport($filter_details)
    {

         $customers = CustomerModel::with('activeOutstandingInvoices')->with('activeOutstandingInvoices.invoicePayment')->with('customerAddress')->with('rep');

        $customers->whereHas('activeOutstandingInvoices', function($q){
            $q->where('total', '>', 'paid_amount');
            $q->where('invoice_code','<>', '');
        });
        $customers->whereHas('activeOutstandingInvoices.invoiceReturnChequeInvoicePayments', function($q){
            $q->whereNotNull('cheque_id')->where('cheque_status',2);
        });


        if($filter_details['customer_id'] != '')
            $customers->where('id', $filter_details['customer_id']);

        if($filter_details['rep'] != '')
            $customers->where('rep_id', $filter_details['rep']);



        $customers = $customers->get();


        $pdf = PDF::loadView('xreports::sales.cheque_return_outstanding.cheque_return_outstanding_report', array('customers' => $customers));

        $path = 'reports/reports/sales';

        if (!file_exists($path)) {
            mkdir($path, 0666, true);
        }
        $path = $path . '/customer_outstanding' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);
    }


    public static function generateLowStockProductsReport($filter_details)
    {

        $low_stock_products = ProductsModel::where('qty_on_hand', '<=', 'reorder_point')->with('category')->where('type', 'stock');

        if($filter_details['product_catagory'] != '')
            $low_stock_products->where('category_id', $filter_details['product_catagory']);

            $low_stock_products=$low_stock_products->get();


        $pdf = PDF::loadView('xreports::inventory.low_stock_products_report.low_stock_products', array('products' => $low_stock_products));

        $path = 'reports/reports/inventory';

        if (!file_exists($path)) {
            mkdir($path, 0666, true);
        }
        $path = $path . '/low_stock_products' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);
    }

}
