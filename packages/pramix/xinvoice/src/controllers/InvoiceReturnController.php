<?php

namespace Pramix\XInvoice\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Carbon\Carbon;
use Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use Pramix\XInvoice\Models\InvoiceReturnProductModel;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;
use Pramix\Templates\Models\GenerateInvoiceReturnModel;

class InvoiceReturnController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        $page = 'invoice_return';
        return view('xinvoice::invoice_return.returns_list')->with('page', $page);
    }

    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_CREDIT_NOTE');

        $invoice = NULL;

        if (isset($request['invoice_id'])) {
            $invoice = InvoiceModel::find($request['invoice_id']);

        }

        $page = 'invoice_return';
        $invoice_returns = '';
        $product_list = ProductsModel::where('type', '!=', 'production')->pluck('item_code', 'id');
        $product_catagory = ProductCategoriesModel::pluck('category_name', 'id');

        return view('xinvoice::invoice_return.create_invoice_return')
            ->with('product_catagory', $product_catagory)
            ->with('product_list', $product_list)
            ->with('page', $page)
            ->with('invoice', $invoice)
            ->with('invoice_returns', $invoice_returns);
    }


    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_CREDIT_NOTE');

        if ($request['customer_id'] != '') {

            $invoice_return_details = new InvoiceReturnModel();
            $invoice_return_details->invoice_return_code = '';
            $invoice_return_details->invoice_return_date = Carbon::now();
            $invoice_return_details->customer_id = $request['customer_id'];
            $invoice_return_details->invoice_id = $request['invoice_id'];
            $invoice_return_details->status = 'D';
            $invoice_return_details->save();

            return response()->json(['status' => 'success', 'invoice_return_details' => $invoice_return_details]);
        }
    }


    public function show($id)
    {
        //
    }


    public function getOutstandingInvoiceList($customer_id)
    {
        $invoice_type = 'all';



        $invoice = InvoiceModel::where('invoice_code', '!=', '')->with('return_invoice')->with('customer')->with('user');



            $invoice->where('customer_id', $customer_id);

            $invoice->where('balance', '>', 0);

                $invoice->where('status', 'I');


        $invoice = $invoice->get();



        $edit_invoice_permission = false;
        if (Auth::user()->can('EDIT_INVOICE')) {
            $edit_invoice_permission = true;
        }

        return Datatables::of($invoice)
            ->addColumn('action', function ($invoice) use ($edit_invoice_permission) {

                $actions = '';
                if ($edit_invoice_permission) {

                    $actions .= ' <a target="="_blank" class = "btn btn-info btn-xs" href="' . url("/invoice/" . $invoice->id . "/edit") . '" id="edit_invoice" data-original-title="" title=""><i class="fa fa-pencil"></i></a> ';
                }

                return $actions;
            })
            ->addColumn('created_by', function ($invoice) {
                return $invoice->user->username;
            })

            ->editColumn('payment_status', function ($invoice) {

                if ($invoice->total <= $invoice->paid_amount)
                    return '<span class="text-success"><strong>Completed</strong></span>';
                elseif ($invoice->total == $invoice->balance) {

                    $count_days = Carbon::parse($invoice->invoice_date)->diffInDays(Carbon::now(), false);

                    return '<span class="text-danger"><strong>Pending</strong></span> ' . $count_days. ' Days';
                } elseif ($invoice->total != $invoice->balance)
                    return '<span class="text-primary"><strong>Partial</strong></span>';


            })
            ->editColumn('status', function ($invoice) {

                if ($invoice->customer == NULL)
                    return '<strong>Quick Sell</strong>';

                if ($invoice->status == 'O')
                    return '<span class="text-danger"><strong>Order</strong></span>';
                elseif ($invoice->status == 'D')
                    return '<span class="text-success"><strong>Ready to Dispatch</strong></span>';
                elseif ($invoice->status == 'I')
                    return '<span class="text-primary">Invoice</span>';

            })
            ->editColumn('total', function ($invoice) {
                return Helper::formatPrice($invoice->total);
            })
            ->editColumn('paid_amount', function ($invoice) {
                return Helper::formatPrice($invoice->paid_amount);
            })
            ->editColumn('balance', function ($invoice) {
                return Helper::formatPrice($invoice->balance);
            })
            ->rawColumns(['payment_status', 'status', 'action', 'customer'])
            ->make(true);
    }


    public function edit($id, Request $request)
    {
        Permission::checkPermission($request, 'EDIT_CREDIT_NOTE');

        $page = 'invoice_return';
        $invoice_return = InvoiceReturnModel::find($id);

        return view('xinvoice::invoice_return.create_invoice_return')
            ->with('page', $page)
            ->with('invoice_return', $invoice_return);
    }


    public function update(Request $request, $id)
    {
        Permission::checkPermission($request, 'EDIT_CREDIT_NOTE');

        $invoice_return_details = InvoiceReturnModel::find($id);

        $return_from_invoices_array = $request['return_from_invoices_array'];

        $status = $request['status'];
        if ($status == 'A') {
            $invoice_return_products = InvoiceReturnProductModel::where('invoice_return_id', $id)->where('status', 0)->get();

            foreach ($invoice_return_products as $invoice_return_product) {
                $product = ProductsModel::find($invoice_return_product->product_id);

                if ($product->type == 'stock' && $invoice_return_product->discarded == 0) {
                    Inventory::increaseInventory($invoice_return_product->product_id, getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'invoice_return'), $invoice_return_details->invoice_return_code, $invoice_return_product->qty, NULL);
                }

                $invoice_return_product->status = 1;
                $invoice_return_product->save();
            }

            $balance = $invoice_return_details->total;

            foreach($return_from_invoices_array as $return_invoice)
            {
                $invoice = InvoiceModel::find($return_invoice);

                if($balance >= $invoice->balance) {
                    $invoice->returned_amount += $invoice->balance;
                    $invoice->balance = 0;
                    $balance = $balance - $invoice->balance;

                }else
                {
                    $invoice->returned_amount += $balance;
                    $invoice->balance -= $balance;
                    $balance = 0;
                }
                $invoice->save();

                if($balance == 0)
                {
                    break;
                }

            }

            $customer = CustomerModel::find($request['customer_id']);
            $customer->credit_balance += $balance;
            $customer->save();
            CustomerModel::updateCustomerOutStanding($customer->id);

        }

        if ($invoice_return_details->invoice_return_code == '') {
            $last_record = InvoiceReturnModel::orderBy('id', 'desc')->where('invoice_return_code', '!=', '')->first();
            $invoice_return_details->invoice_return_code = OptionModel::generateCode('CN', 4, $last_record->invoice_return_code ?? NULL);
        }

        $invoice_return_details->customer_id = !empty($request['customer_id']) ? ($request['customer_id']) : 0;
        $invoice_return_details->invoice_id = !empty($request['invoice_id']) ? ($request['invoice_id']) : 0;
        $invoice_return_details->remarks = $request['remarks'];
        $invoice_return_details->status = $status;
        $invoice_return_details->save();

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'invoice_return_details' => $invoice_return_details]);
    }


    public function destroy($id)
    {
        //
    }

    public function getProductsByCategory(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        $category_id = $request['category_id'];
        $invoice_id = $request['invoice_id'];


        if ($category_id != null) {
            $products = DB::table('invoice_products')
                ->join('product', 'invoice_products.product_id', '=', 'product.id')
                ->join('product_categories', 'product.category_id', '=', 'product_categories.id')
                ->select('product.*')
                ->where('product_categories.id', '=', $category_id)
                ->where('invoice_products.invoice_id', '=', $invoice_id)
                ->get();
        } else {
            $products = DB::table('invoice_products')
                ->join('product', 'invoice_products.product_id', '=', 'product.id')
                ->where('invoice_products.invoice_id', '=', $invoice_id)
                ->select('product.*')
                ->get();
        }

        return response()->json(['status' => 'success', 'products' => $products]);
    }

    public function getInvoiceDetails(Request $request)
    {

        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        if ($request['invoice_id'] != '') {
            $invoice_id = $request['invoice_id'];
            $invoice = InvoiceModel::find($invoice_id);
            $products = DB::table('invoice_products')
                ->join('product', 'invoice_products.product_id', '=', 'product.id')
                ->select('product.*')
                ->whereNull('invoice_products.deleted_at')
                ->where('invoice_products.invoice_id', '=', $invoice_id)
                ->get();
            $categories = DB::table('invoice_products')
                ->join('product', 'invoice_products.product_id', '=', 'product.id')
                ->join('product_categories', 'product.category_id', '=', 'product_categories.id')
                ->select('product_categories.*')
                ->whereNull('invoice_products.deleted_at')
                ->where('invoice_products.invoice_id', '=', $invoice_id)
                ->get()
                ->unique();
            return response()->json(['status' => 'success', 'invoice' => $invoice, 'products' => $products, 'categories' => $categories]);
        }
    }

    public function getInvoiceReturnProductDetails(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        if ($request['invoice_id'] != '') {
            $invoice_return_product_detail = InvoiceProductsModel::where('invoice_id', $request['invoice_id'])->where('product_id', $request['product_id'])->with('product')->first();
            return response()->json(['status' => 'success', 'invoice_return_product_detail' => $invoice_return_product_detail]);
        }

    }


    public function getInvoiceReturnList(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        $invoice_returns = InvoiceReturnModel::where('invoice_return_code', '!=', '')->with('customer')->with('user')->get();
        $edit_invoicer_permission = false;
        if (Auth::user()->can('EDIT_CREDIT_NOTE')) {
            $edit_invoicer_permission = true;
        }

        return Datatables::of($invoice_returns)
            ->addColumn('action', function ($invoice_returns) use ($edit_invoicer_permission) {
                if ($edit_invoicer_permission) {
                    return '<a class = "btn btn-info btn-xs" href="' . url("/invoice_return/" . $invoice_returns->id . "/edit") . '" id="edit_customer" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }
            })
            ->addColumn('customer', function ($invoice_returns) {
                if (isset($invoice_returns->customer))
                    return $invoice_returns->customer->fullname;
            })
            ->addColumn('created_by', function ($invoice_returns) {
                if (isset($invoice_returns->user->username))
                    return $invoice_returns->user->username;
            })
            ->editColumn('status', function ($invoice_returns) {
                if ($invoice_returns->status == 'D')
                    return '<span class="text-danger"><strong>Draft</strong></span>';
                elseif ($invoice_returns->status == 'A')
                    return '<span class="text-success"><strong>Completed</strong></span>';
            })
            ->editColumn('total', function ($invoice_returns) {
                return Helper::formatPrice($invoice_returns->total);
            })
            ->editColumn('paid_amount', function ($invoice_returns) {
                return Helper::formatPrice($invoice_returns->paid_amount);
            })
            ->editColumn('balance', function ($invoice_returns) {
                return Helper::formatPrice($invoice_returns->balance);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);

    }

    public function addInvoiceReturnProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        parse_str($request['product_details'], $product_details);

        $invoice_return_id = $request['invoice_return_id'];
        $record_id = $request['record_product_id'];
        $invoice_id = $request['invoice_id'];
        $product_id = $product_details['products'];

        $validator = Validator::make($product_details, [
            'unit_price' => 'required',
            'quantity' => 'required',
        ]);

        if (!$validator->passes()) {

            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

//        if ($record_id == NULL) {
//            $invoice_prod = InvoiceReturnProductModel::where('invoice_return_id', $invoice_return_id)->where('product_id', $product_details['products'])->first();
//            if ($invoice_prod != NULL)
//                return response()->json(['status' => 'error', 'msg' => __('You have already added this product')]);
//        }

        $invoice_return = DB::table('invoice_return_product')
            ->join('invoice_return', 'invoice_return_product.invoice_return_id', '=', 'invoice_return.id')
            ->where('invoice_return.invoice_id', $invoice_id)
            ->where('invoice_return_product.status', 1)
            ->groupBy('product_id')
            ->sum('qty');

        $product = ProductsModel::find($product_details['products']);


        $invoice_product_qty = InvoiceProductsModel::where('invoice_id', $invoice_id)->where('product_id', $product_id)->sum('qty');
        if ($invoice_product_qty < $invoice_return)
            return response()->json(['status' => 'error', 'msg' => __('You have already returned all products')]);


        $prod = InvoiceReturnProductModel::where('invoice_return_id', $invoice_return_id)->where('id', $record_id)->first();

        if ($prod == NULL)
            $prod = new InvoiceReturnProductModel();

        $quantity = !empty($product_details['quantity']) ? $product_details['quantity'] : 0;
        $unit_price = !empty($product_details['unit_price']) ? $product_details['unit_price'] : 0;
        $discount = !empty($product_details['discount']) ? $product_details['discount'] : 0;
        if ($product_details['discount_type'] == 'P') {
            $tot = $quantity * $unit_price;
            $sub_tot = $tot * (100 - $discount) / 100;

        } else {
            $tot = $quantity * $unit_price;
            $sub_tot = $tot - $discount;

        }


        $prod->invoice_return_id = $invoice_return_id;
        $prod->product_id = $product_details['products'];
        $prod->description = isset($product_details['description']) ? $product_details['description'] : '';
        $prod->qty = !empty($product_details['quantity']) ? $product_details['quantity'] : 0;
        $prod->unit_price = !empty($product_details['unit_price']) ? $product_details['unit_price'] : 0;
        $prod->discount = $discount;
        $prod->discount_type = isset($product_details['discount_type']) ? $product_details['discount_type'] : 'P';
        $prod->sub_total = $sub_tot;
        $prod->discarded = isset($product_details['checked_discarded']) ? $product_details['checked_discarded'] : '0';
        $prod->status = 0;
        $prod->save();

        return response()->json(['status' => 'success', 'msg' => __('Success Details'), 'invoice_return_id' => $invoice_return_id]);

    }

    public function getInvoiceReturnProduct($invoice_return_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        if ($invoice_return_id == '') {
            $products = [];
        } else {
            $products = InvoiceReturnProductModel::where('invoice_return_id', $invoice_return_id)->with('product')->get();
        }

        return Datatables::of($products)
            ->editColumn('actions', function ($products) {
                if ($products->status != 1)
                    return "<button class='btn btn-warning btn-sm icon-edit'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-sm icon-circle-cross'><i class='fa fa-remove'></i></button>";
                else
                    return "<span class='label label-success'>Completed</span>";
            })
            ->editColumn('category', function ($products) {
                return $products->product->category->category_name;
            })
            ->editColumn('item', function ($products) {
                return $products->product->item_code;
            })
            ->editColumn('discount_type_show', function ($products) {
                if ($products->discount_type == 'P')
                    return 'Pracentage';
                else
                    return 'Amount';
            })
            ->editColumn('sub_total', function ($products) {
                return $products->sub_total;
            })
            ->editColumn('quantity', function ($products) {
                return $products->qty;
            })
            ->editColumn('stock_status', function ($products) {
                $status =  null;
                if ($products->discarded == 1)
                    $status =  'Discarded';
                elseif ($products->status == 1)
                    $status =  'In Stock';
                elseif ($products->status == 0)
                    $status =  'Pending';

                return $status;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function deleteInvoiceReturnProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        parse_str($request['invoice_price_details'], $price_details);
        $id = $request['record_id'];
        $invoice_return_id = $request['invoice_return_id'];

        InvoiceReturnProductModel::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'msg' => __('Success deleted')]);

    }

    public function getInvoices(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        if ($request['customer_id'] != '')
            $invoices = InvoiceModel::where('invoice_code', '!=', '')->where('customer_id', $request['customer_id'])->get();

        else
            $invoices = InvoiceModel::where('invoice_code', '!=', '')->get();

        return response()->json(['status' => 'success', 'invoices' => $invoices]);
    }

    public function calInvoicePrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');


        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;

        if ($request['invoice_return_id'] == null)
            return 0;

        parse_str($request['invoice_return_price_details'], $prices);
        $invoice_return_id = $request['invoice_return_id'];
        if (isset($prices['checked_vat'])) {
            $vat = true;
        }
        if (isset($prices['checked_nbt'])) {
            $nbt = true;
        }

        $record = InvoiceReturnModel::find($invoice_return_id);
        if ($record == NULL)
            $record = new InvoiceProductsModel();

        $sub_tot = InvoiceReturnProductModel::where('invoice_return_id', $invoice_return_id)->sum('sub_total');


        $discount = !empty($prices['invoice_return_discount']) ? $prices['invoice_return_discount'] : 0;
        $discount_type = !empty($prices['discount_type']) ? $prices['discount_type'] : 0;
//            $paid_amount = InvoicePaymentModel::where('invoice_id', $invoice_id)->sum('payment_amount');;


        if ($discount_type == 'P') {
            $total = $sub_tot * (100 - $discount) / 100;
        } else {
            $total = $sub_tot - $discount;
        }

        if ($vat) {
            $vat_amount = $total * (getConfig('VAT_PERCENTAGE') / 100);
        }
        if ($nbt) {
            $nbt_amount = $total * (getConfig('NBT_PERCENTAGE') / 100);
        }

        $total = $total + $vat_amount + $nbt_amount;


        $record->sub_total = $sub_tot;
        $record->discount = $discount;
        $record->discount_type = $discount_type;
        $record->vat_amount = $vat_amount;
        $record->nbt_amount = $nbt_amount;
        $record->total = $total;
        $record->balance = $total - ($prices['refund'] ?? 0);
        $record->refund = $prices['refund'];
        $record->customer_credit = $total - $prices['refund'];
        $record->save();


        return response()->json(['status' => 'success', 'msg' => __('Record Save Success'), 'record' => $record]);


    }

    public function generateInvoiceReturnPDF(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');

        $invoice_return_id = $request['invoice_return_id'];
        $pdf = GenerateInvoiceReturnModel::generateInvoiceReturn($invoice_return_id);

        return response()->json(['status' => 'success', 'url' => $pdf]);

    }
}
