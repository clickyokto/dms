<?php

namespace Pramix\XReports\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use PDF;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XInvoice\Models\InvoicePaymentModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use Pramix\XInvoice\Models\InvoiceReturnProductModel;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;


class PaymentSummaryReport extends Model
{
    public static function generatePaymentSummaryReport($filter_details = NULL)
    {

        $date_range = NULL;
        $from_date = NULL;
        $end_date = NULL;
        $customer_id = NULL;
        $customer_details = NULL;
        $payment_method = $filter_details['payment_method'];

        if ($filter_details['date_range'] != '') {
            $date_range_details = ReportsModel::getReportTimeRange($filter_details);
            $date_range = $filter_details['date_range'];
            $from_date = $date_range_details['from_date'];
            $end_date = $date_range_details['end_date'];
        }


        if (isset($filter_details['customer_id']) && $filter_details['customer_id'] != '') {
            $customer_id = $filter_details['customer_id'];
        }


        $invoice_payments = InvoicePaymentModel::where('status', 1)->with('invoice');

        if ($customer_id != NULL) {
            $invoice_payments->whereHas('invoice', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            });
        }

        if ($date_range != null) {
            $invoice_payments->whereDate('cheque_date', '>=', $from_date);
        }
        if ($date_range != null) {
            $invoice_payments->whereDate('cheque_date', '<=', $end_date);
        }

        if ($filter_details['payment_method'] != 'All') {
            if ($filter_details['payment_method'] == 'cash')
                $invoice_payments->where('payment_method','cash');
            elseif ($filter_details['payment_method'] == 'cheque')
                $invoice_payments->where('payment_method','cheque');
            elseif ($filter_details['payment_method'] == 'debit')
                $invoice_payments->where('payment_method','debit');

        }
        $invoice_payments = $invoice_payments->get();

//

        if($customer_id != NULL)
        {
            $customer_details = CustomerModel::find($customer_id);
        }
        $pdf = PDF::loadView('xreports::sales.payment_summary.payment_summary', array('invoice_payments' => $invoice_payments, 'from_date' => $from_date, 'end_date' => $end_date, 'payment_method' => $payment_method, 'customer_details' => $customer_details));

        $path = 'reports/reports/payment';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path . '/payment_summary_report_' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);

    }
}
