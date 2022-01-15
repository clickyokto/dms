<?php

namespace Pramix\XReports\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use PDF;
use Pramix\XInvoice\Models\InvoicePaymentModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use Pramix\XInvoice\Models\InvoiceReturnProductModel;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderPaymentModel;
use Pramix\XSupplier\Models\SupplierModel;
use TheSeer\Tokenizer\NamespaceUri;


class BillingSummaryReport extends Model
{
    public static function generateBillingSummaryReport($filter_details = NULL)
    {
        $date_range = NULL;
        $from_date = NULL;
        $end_date = NULL;
        $supplier_id = NULL;
        $supplier_details = NULL;
        $payment_method = $filter_details['payment_method'];

        if ($filter_details['date_range'] != '') {
            $date_range_details = ReportsModel::getReportTimeRange($filter_details);
            $date_range = $filter_details['date_range'];
            $from_date = $date_range_details['from_date'];
            $end_date = $date_range_details['end_date'];
        }


        if (isset($filter_details['supplier_id']) && $filter_details['supplier_id'] != '') {
            $supplier_id = $filter_details['supplier_id'];
        }

        $purchase_order_payments = PurchaseOrderPaymentModel::where('status', 1)->with('purchase_order');

        if ($supplier_id != NULL) {
            $purchase_order_payments->whereHas('purchase_order', function ($query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            });
        }

        if ($date_range != null) {
            $purchase_order_payments->whereDate('cheque_date', '>=', $from_date);
        }
        if ($date_range != null) {
            $purchase_order_payments->whereDate('cheque_date', '<=', $end_date);
        }

        if ($filter_details['payment_method'] != 'All') {
            if ($filter_details['payment_method'] == 'cash')
                $purchase_order_payments->where('payment_method','cash');
            elseif ($filter_details['payment_method'] == 'cheque')
                $purchase_order_payments->where('payment_method','cheque');
            elseif ($filter_details['payment_method'] == 'debit')
                $purchase_order_payments->where('payment_method','debit');

        }
        $purchase_order_payments = $purchase_order_payments->get();

//

        if($supplier_id != NULL)
        {
            $supplier_details = SupplierModel::find($supplier_id);
        }



        $pdf = PDF::loadView('xreports::purchasing.billing_summary.billing_summary', array('purchase_order_payments' => $purchase_order_payments, 'from_date' => $from_date, 'end_date' => $end_date, 'payment_method' => $payment_method, 'supplier_details' => $supplier_details));

        $path = 'reports/reports/bills';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path . '/billing_summary_report_' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);

    }
}
