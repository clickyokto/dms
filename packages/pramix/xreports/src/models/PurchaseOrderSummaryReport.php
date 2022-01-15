<?php

namespace Pramix\XReports\Models;

use Illuminate\Database\Eloquent\Model;
use Pramix\XInvoice\Models\InvoiceModel;
use PDF;
use Carbon\Carbon;
use Pramix\XPurchaseOrder\Models\PurchaseOrderModel;


class PurchaseOrderSummaryReport extends Model
{
    public static function generatePurchaseOrderSummaryReport($filter_details = NULL)
    {
        $date_range = NULL;
        $from_date = NULL;
        $end_date = NULL;
        $product_id = NULL;
        $category_id = NULL;

        if ($filter_details['date_range'] != '') {
            $date_range_details = ReportsModel::getReportTimeRange($filter_details);
            $date_range = $filter_details['date_range'];
            $from_date = $date_range_details['from_date'];
            $end_date = $date_range_details['end_date'];
        }


        $purchase_orders = PurchaseOrderModel::where('status', 'A')->where('purchase_order_code','!=','');
        if ($product_id != NULL) {
            $purchase_orders->where('product_id', $product_id);
        }

        if ($date_range != null) {
            $purchase_orders->whereDate('purchase_order_date', '>=', $from_date);
        }
        if ($date_range != null) {
            $purchase_orders->whereDate('purchase_order_date', '<=', $end_date);
        }

        if ($filter_details['payment_status'] != 'All') {
            if ($filter_details['payment_status'] == 'Complete')
                $purchase_orders->whereRaw('total <= paid_amount');
            elseif ($filter_details['payment_status'] == 'Pending')
                $purchase_orders->whereRaw('total = balance');
            elseif ($filter_details['payment_status'] == 'Partial')
                $purchase_orders->whereRaw('total > paid_amount')->where('paid_amount','!=' ,0);

        }
        $purchase_orders = $purchase_orders->get();


//        $product_details = NULL;
//        $category_details = NULL;
//        if($product_id != NULL)
//        {
//            $product_details = ProductsModel::find($product_id);
//        }
//
//        if($category_id !=NULL)
//        {
//            $category_details = ProductCategoriesModel::find($category_id);
//        }

        $pdf = PDF::loadView('xreports::purchasing.purchasing_summary.purchasing_summary', array('purchase_orders' => $purchase_orders, 'from_date' => $from_date, 'end_date' => $end_date,'payment_status'=>$filter_details['payment_status']))->setPaper('a4', 'landscape');

        $path = 'reports/reports/purchasing';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path . '/sales_by_product_summary_report_' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);

    }
}
