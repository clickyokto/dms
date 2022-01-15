<?php

namespace Pramix\XReports\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use PDF;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use Pramix\XInvoice\Models\InvoiceReturnProductModel;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;


class SalesByProductSummaryReport extends Model
{
    public static function generateSalesByProductSummaryReport($filter_details = NULL)
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

        if (isset($filter_details['products']) && $filter_details['products'] != '') {
            $product_id = $filter_details['products'];
        }
        if (isset($filter_details['product_catagory']) && $filter_details['product_catagory'] != '') {
            $category_id = $filter_details['product_catagory'];
        }

        $invoice_products = InvoiceProductsModel::where('status', 1)->with('product')->with('invoice');
        if ($product_id != NULL) {
            $invoice_products->where('product_id', $product_id);
        }
        if ($category_id != NULL) {
            $invoice_products->whereHas('product', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }
        if ($date_range != null) {
            $invoice_products->whereHas('invoice', function ($query) use ($from_date) {
                $query->whereDate('invoice_date', '>=', $from_date);
            });

        }
        if ($date_range != null) {
            $invoice_products->whereHas('invoice', function ($query) use ($end_date) {
                $query->whereDate('invoice_date', '>=', $end_date);
            });

        }
        $invoice_products = $invoice_products->get();

        $qty_sum = array();
        $sub_total_sum = array();

        foreach ($invoice_products as $invoice_product) {
            $invoice_return = InvoiceReturnModel::where('invoice_id', $invoice_product->invoice_id)
                ->whereHas('invoiceReturnProducts', function ($query) use ($invoice_product) {
                    $query->where('product_id', $invoice_product->product_id);
                })
                ->with('invoiceReturnProducts')->first();

            if (!isset($qty_sum[$invoice_product->product_id]))
                $qty_sum[$invoice_product->product_id] = 0;
            if (!isset($sub_total_sum[$invoice_product->product_id]))
                $sub_total_sum[$invoice_product->product_id] = 0;

            $invoice_return_products_qty_sum = 0;
            $invoice_return_products_unit_price_sum = 0;
            if(isset($invoice_return->invoiceReturnProducts)) {
                $invoice_return_products_qty_sum = $invoice_return->invoiceReturnProducts->sum('qty') ?? 0;
                $invoice_return_products_unit_price_sum = $invoice_return->invoiceReturnProducts->sum('unit_price') ?? 0;

            }

            $qty_sum[$invoice_product->product_id] += $invoice_product->qty - $invoice_return_products_qty_sum;
            $sub_total_sum[$invoice_product->product_id] += ($invoice_product->unit_price * $invoice_product->qty) - ($invoice_return_products_qty_sum * $invoice_return_products_unit_price_sum);
        }

        $product_details = NULL;
        $category_details = NULL;
        if($product_id != NULL)
        {
            $product_details = ProductsModel::find($product_id);
        }

        if($category_id !=NULL)
        {
            $category_details = ProductCategoriesModel::find($category_id);
        }

        $pdf = PDF::loadView('xreports::sales.sales_by_product_summary.sales_by_product_summary_report', array('invoice_products' => $invoice_products, 'from_date' => $from_date, 'end_date' => $end_date, 'qty_sum' => $qty_sum, 'sub_total_sum' => $sub_total_sum, 'product_details'=>$product_details,'category_details' => $category_details));

        $path = 'reports/reports/sales';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path . '/sales_by_product_summary_report_' . str_replace('-', '_', Carbon::now()) . '.pdf';
        $path = str_replace(':', '_', $path);

        $pdf->save($path);
        return url($path);

    }
}
