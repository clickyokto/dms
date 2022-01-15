<?php

namespace Pramix\XReports\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDF;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceModel;


class CustomerOutstandingReport extends Model
{
    public static function generateCustomerOutstandingReport($filter_details)
    {


        $customers = CustomerModel::with('activeOutstandingInvoices')


            ->with('customerAddress')->with('rep');

        $customers->whereHas('activeOutstandingInvoices', function($q){
            $q->where('total', '>', 'paid_amount');
            $q->where('invoice_code','<>', '');
        });
        if($filter_details['customer_city'] != '') {
            $customers->whereHas('customerAddress', function($q) use($filter_details){
                $q->where('city_id',  $filter_details['customer_city']);
            });
        }
        if($filter_details['customer_id'] != '')
            $customers->where('id', $filter_details['customer_id']);

        if($filter_details['rep'] != '')
                    $customers->where('rep_id', $filter_details['rep']);



        $customers = $customers->get();






        $pdf = PDF::loadView('xreports::sales.customer_outstanding.customer_outstanding_report', array('customers' => $customers));

        $path = 'reports/reports/sales';

        if (!file_exists($path)) {
            mkdir($path, 0666, true);
        }
        $path = $path . '/customer_outstanding' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);
    }

    public static function generateCustomerOutstandingPeriodWiseReport($filter_details)
    {

        $customers = CustomerModel::with('activeOutstandingInvoices')->with('customerAddress')->with('rep');

        $customers->whereHas('activeOutstandingInvoices', function($q){
            $q->where('total', '>', 'paid_amount');
            $q->where('invoice_code','<>', '');
        });
        if($filter_details['customer_id'] != '')
            $customers->where('id', $filter_details['customer_id']);

        if($filter_details['customer_city'] != '') {
            $customers->whereHas('customerAddress', function($q) use($filter_details){
                $q->where('city_id',  $filter_details['customer_city']);
            });
        }

        if($filter_details['rep'] != '')
            $customers->where('rep_id', $filter_details['rep']);
        $customers = $customers->get();

        foreach($customers as $customer)
        {

            $customer['invoice_total'] = InvoiceModel::where('customer_id', $customer->id)->where('total', '>', 'paid_amount')->where('invoice_code', '!=', '')->sum('balance');
            $customer['less_30_days'] = InvoiceModel::where('customer_id', $customer->id)->whereBetween('invoice_date', [Carbon::now()->subDays(30), Carbon::now()])->where('invoice_code', '!=', '')->where('total', '>', 'paid_amount')->sum('balance');

            $customer['between_31_60_days'] = InvoiceModel::where('customer_id', $customer->id)->whereBetween('invoice_date', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)])->where('invoice_code', '!=', '')->where('total', '>', 'paid_amount')->sum('balance');
            $customer['between_61_90_days'] = InvoiceModel::where('customer_id', $customer->id)->whereBetween('invoice_date', [Carbon::now()->subDays(90), Carbon::now()->subDays(61)])->where('invoice_code', '!=', '')->where('total', '>', 'paid_amount')->sum('balance');
            $customer['over_91'] = InvoiceModel::where('customer_id', $customer->id)->where('invoice_date','<',Carbon::now()->subDays(90))->where('invoice_code', '!=', '')->where('total', '>', 'paid_amount')->sum('balance');

        }



        $pdf = PDF::loadView('xreports::sales.customer_outstanding_period_wise.customer_outstanding_period_wise_report', array('customers' => $customers));

        $path = 'reports/reports/sales';

        if (!file_exists($path)) {
            mkdir($path, 0666, true);
        }
        $path = $path . '/customer_outstanding_period_wise' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);


    }
}
