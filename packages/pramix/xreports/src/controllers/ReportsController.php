<?php

namespace Pramix\XReports\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pramix\XReports\Models\BillingSummaryReport;
use Pramix\XReports\Models\CustomerOutstandingReport;
use Pramix\XReports\Models\InventoryMovementSummaryReport;
use Pramix\XReports\Models\PaymentSummaryReport;
use Pramix\XReports\Models\ProductsReport;
use Pramix\XReports\Models\PurchaseOrderSummaryReport;
use Pramix\XReports\Models\ReportsModel;
use Pramix\XReports\Models\SalesByProductSummaryReport;
use Pramix\XReports\Models\SalesOrderSummaryReport;

class ReportsController extends Controller
{
   public function index()
   {
       return view('xreports::index');
   }

   public function inventoryMovementSummaryReport(Request $request)
   {
       return view('xreports::inventory.inventory_movement_summary.index');
   }

   public function productsReport()
   {
       return view('xreports::inventory.products_report.index');
   }

   public function salesOrderSummary()
   {
       return view('xreports::sales.sales_order_summary_report.index');
   }

   public function salesByProductSummary()
   {
       return view('xreports::sales.sales_by_product_summary.index');
   }

    public function paymentSummaryReport()
    {
        return view('xreports::sales.payment_summary.index');
    }

    public function purchasingSummaryReport()
    {
        return view('xreports::purchasing.purchasing_summary.index');
    }

    public function billingSummaryReport()
    {
        return view('xreports::purchasing.billing_summary.index');
    }

    public function customerOutstandingReport()
    {
        return view('xreports::sales.customer_outstanding.index');
    }

    public function customerOutstandingReportPeriodWise()
    {
        return view('xreports::sales.customer_outstanding_period_wise.index');
    }

    public function chequeReturnOutstandingReport()
    {
        return view('xreports::sales.cheque_return_outstanding.index');

    }

    public function lowStockProductsReport()
    {
        return view('xreports::inventory.low_stock_products_report.index');

    }


    public function generateProductsReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  ProductsReport::generateProductsReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }


    public function generateCustomerOutstandingReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  CustomerOutstandingReport::generateCustomerOutstandingReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }

    public function generateCustomerOutstandingPeriodWiseReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  CustomerOutstandingReport::generateCustomerOutstandingPeriodWiseReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }



    public function generateInventoryMovementSummaryReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
      $report_url =  InventoryMovementSummaryReport::generateInventoryMovementSummaryReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);

    }

    public function generateSalesByProductSummaryReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  SalesByProductSummaryReport::generateSalesByProductSummaryReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }

    public function generateSalesOrderSummaryReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  SalesOrderSummaryReport::generateSalesOrderSummaryReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }

    public function generatePurchaseOrderSummaryReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  PurchaseOrderSummaryReport::generatePurchaseOrderSummaryReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }

    public function generateBillingSummaryReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  BillingSummaryReport::generateBillingSummaryReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }

    public function generatePaymentSummaryReport(Request $request)
    {
        parse_str($request['filter_details'], $filter_details);
        $report_url =  PaymentSummaryReport::generatePaymentSummaryReport($filter_details);
        return response()->json(['status' => 'success', 'report_url' => $report_url]);
    }

public function generateChequeReturnOutstandingReport(Request $request)
{
    parse_str($request['filter_details'], $filter_details);
    $report_url =  ReportsModel::generateChequeReturnReport($filter_details);
    return response()->json(['status' => 'success', 'report_url' => $report_url]);
}


public function generateLowStockProductsReport(Request $request)
{
    parse_str($request['filter_details'], $filter_details);
    $report_url =  ReportsModel::generateLowStockProductsReport($filter_details);
    return response()->json(['status' => 'success', 'report_url' => $report_url]);
}


}
