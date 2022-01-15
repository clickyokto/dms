<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGRN\Models\GRNModel;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoicePaymentModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use Pramix\XPayment\Models\ChequeModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderModel;
use Pramix\XQuotation\Models\QuotationModel;
use Pramix\XReports\Models\InventoryMovementSummaryReport;
use Pramix\XSupplier\Models\SupplierModel;
use DB;
use Pramix\XUser\Models\Role;
use Pramix\XUser\Models\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::today()->startOfDay();
        $next_day = $today->today()->addDay()->endOfDay();

        $low_stock_products = ProductsModel::where('qty_on_hand', '<=', 'reorder_point')->where('type', 'stock')->limit(6)->get();
        $pending_cheques = InvoicePaymentModel::with('invoice')->where('payment_method', 'cheque')->where('cheque_status', 0);
        $pending_cheques->whereBetween('cheque_date', [$today, $next_day]);
        $pending_cheques = $pending_cheques->get();
        $not_clearing_cheques = ChequeModel::whereBetween('cheque_date', [$today, $next_day])->get();
        $customer_count = CustomerModel::count();

        $purchase_orders_count = PurchaseOrderModel::count();



        $now = Carbon::now();
        $submonth = $now->firstOfMonth();

        $sales_total = InvoiceModel::select(array(
            DB::raw('DATE(`invoice_date`) as `date`'),
            DB::raw('SUM(total) as `sum`')
        ))
            ->where('invoice_date', '>=', $submonth)
            ->where('status','A')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->pluck('sum', 'date');



        $total_paid = InvoiceModel::select(array(
            DB::raw('DATE(`invoice_date`) as `date`'),
            DB::raw('SUM(paid_amount) as `sum`')
        ))
            ->where('invoice_date', '>=', $submonth)
            ->groupBy('date')
            ->where('status','A')
            ->orderBy('date', 'DESC')
            ->pluck('sum', 'date');




        $salestotal = '';
        $salespaid = '';
        $dates = '';

        for ($date = $submonth; $date <= Carbon::tomorrow(); $date->addDay()) {

            if (isset($sales_total[$date->format('Y-m-d')])) {
                $salestotal .= $sales_total[$date->format('Y-m-d')] . ',';
            } else {
                $salestotal .= 0 . ',';

            }

            if (isset($total_paid[$date->format('Y-m-d')])) {
                $salespaid .= $total_paid[$date->format('Y-m-d')] . ',';
            } else {
                $salespaid .= 0 . ',';
            }

            $dates .= '"' . $date->format('M-d') . '",';
        }


        $invoice_return_count = InvoiceReturnModel::where('invoice_return_code', '!=', '')->count();
        $invoice_count = InvoiceModel::where('invoice_code', '!=', '')->where('status', 'I')->count();
        $payment_count = InvoicePaymentModel::where('payment_code', '!=', '')->count();
        $grn_count = GRNModel::where('grn_code', '!=', '')->count();;



        return view('home')
            ->with('not_clearing_cheques', $not_clearing_cheques)
            ->with('invoice_count', $invoice_count)
            ->with('invoice_return_count', $invoice_return_count)
            ->with('payment_count', $payment_count)
            ->with('grn_count', $grn_count)
            ->with('pending_cheques', $pending_cheques)
            ->with('purchase_orders_count',$purchase_orders_count)
            ->with('low_stock_products', $low_stock_products)
            ->with('salestotal', $salestotal)
            ->with('dates',$dates)
            ->with('salespaid', $salespaid);
    }


    public function bulk(){
        $products = ProductsModel::all();

        return view('bulk-price/index', compact('products'));
    }

    public function bulkUpdate(Request $request){
//        return $request->product;
        foreach ($request->product as $data) {

            $product = ProductsModel::find($data['id']);
            $product->price = $data['price'];
            $product->save();
        }

        return redirect(route('bulk'));
    }

}
