<?php

namespace Pramix\XInvoice\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Carbon\Carbon;
use Countries;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pramix\Templates\Models\GenerateInvoiceModel;
use Pramix\Templates\Models\GeneratePaymentPrintModel;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XGeneral\Models\CommentsModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoicePaymentModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XInvoice\Models\InvoiceRecurringModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use Pramix\XPayment\Models\ChequeModel;
use Pramix\XProduct\Models\ProductCategoriesModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XUser\Models\Permission;
use Pramix\XUser\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;


class
InvoiceController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');

        $page = 'invoice';

        $invoice_type = 'all';

        if (isset($request['invoice_type']))
            $invoice_type = $request['invoice_type'];

        return view('xinvoice::invoice_list')
            ->with('invoice_type', $invoice_type)
            ->with('page', $page);
    }


    public function create(Request $request)
    {

        Permission::checkPermission($request, 'ADD_INVOICE');

        $countryList = json_decode(Countries::getList(App::getLocale(), 'json'));
        $allow_comment = TRUE;
        $page = 'invoice';
        $products = 'products';
        $product_categories = ProductCategoriesModel::all();
        return view('xinvoice::create_invoice')
            ->with('countryList', $countryList)
            ->with('allow_comment', $allow_comment)
            ->with('page', $page)
            ->with('products', $products)
            ->with('product_categories', $product_categories);

    }


    public function store(Request $request)
    {
        Permission::checkPermission($request, 'ADD_INVOICE');
        if ($request['customer_id'] != '' || $request['quick_sell'] == 1) {
            if (isset($request['customer_id'])) {
                $customer = CustomerModel::find($request['customer_id']);
                $oldest_invoice = InvoiceModel::
                where('customer_id', $request['customer_id'])
                    ->where('invoice_code', '!=', '')
                    ->where('status', 'I')
                    ->orderBy('invoice_date', 'asc')
                    ->whereRaw('total' > 'paid_amount')
                    ->first();
                if ($oldest_invoice != null) {
                    $customer->outstanding_max_days;
                    $count_days = Carbon::parse($oldest_invoice->invoice_date)->diffInDays(Carbon::now(), false);
                    if ($customer->outstanding_max_days <= $count_days) {
                        return response()->json(['status' => 'error', 'msg' => 'Suspended Customer']);
                    }
                }
            }

            $invoice_details = new InvoiceModel();
            $invoice_details->invoice_code = '';
            $invoice_details->invoice_date = Carbon::now();
            $invoice_details->customer_id = $request['customer_id'];
            $invoice_details->invoice_company = getConfigValue('INVOICE_COMPANY');
            $invoice_details->vat_amount = 0;
            $invoice_details->nbt_amount = 0;
            $invoice_details->project_id = !empty($request['project_id']) ? $request['project_id'] : null;
            $invoice_details->rep_id = $request['rep_id'];
            $invoice_details->status = 'O';
            $invoice_details->save();
            return response()->json(['status' => 'success', 'invoice_details' => $invoice_details]);
        }
    }

    public function edit($id, Request $request)
    {
        Permission::checkPermission($request, 'EDIT_INVOICE');
        $checked_recurring_status = null;
        $invoice = InvoiceModel::find($id);
        $recurring = InvoiceRecurringModel::where('invoice_id', $id)->first();
        if ($recurring != null)
            $checked_recurring_status = $recurring->status;
        $product_list = ProductsModel::where('type', '!=', 'production')->pluck('item_code', 'id');
        $product_catagory = ProductCategoriesModel::pluck('category_name', 'id');
        $credit_note = InvoiceReturnModel::where('invoice_id', $id)->get();
        $page = 'invoice';

        return view('xinvoice::create_invoice')
            ->with('product_catagory', $product_catagory)
            ->with('product_list', $product_list)
            ->with('invoice', $invoice)
            ->with('recurring', $recurring)
            ->with('checked_recurring_status', $checked_recurring_status)
            ->with('page', $page)
            ->with('credit_note', $credit_note);
    }

    public function update(Request $request, $id)
    {
        Permission::checkPermission($request, 'ADD_INVOICE');

        $invoice_details = InvoiceModel::find($id);
        $status = $request['status'];

        if ($status == 'O') {
$customer = CustomerModel::find($request['customer_id']);
            $oldest_invoice = InvoiceModel::
            where('customer_id', $request['customer_id'])
                ->where('invoice_code', '!=', '')
                ->where('status', 'I')
                ->orderBy('invoice_date', 'asc')
                ->whereRaw('total' > 'paid_amount')
                ->first();
            if ($oldest_invoice != null) {
                $customer->outstanding_max_days;
                $count_days = Carbon::parse($oldest_invoice->invoice_date)->diffInDays(Carbon::now(), false);
                if ($customer->outstanding_max_days <= $count_days) {
                    return response()->json(['status' => 'error', 'msg' => 'Suspended Customer']);
                }
            }
        }



        if ($request['status'] == '' || $request['status'] == null) {
            $status = $invoice_details->status;
        }
        if (isset($request['quick_sell']) && $request['quick_sell'] == '1') {
            if ($invoice_details->balance > 0)
                return response()->json(['status' => 'error', 'msg' => 'You can not continue quick sell without payments']);
            $status = 'Q';
        }

        if ($status == 'I' || $status == 'Q') {
            $invoice_products = InvoiceProductsModel::where('invoice_id', $id)->where('status', 0)->get();
//            foreach ($invoice_products as $invoice_product) {
//                $product = ProductsModel::find($invoice_product->product_id);
//                $available_stock = Inventory::getProductStock($product->id, $invoice_product->store_id);
//                if ($product->type == 'stock' && $available_stock < $invoice_product->qty)
//                    return response()->json(['status' => 'error', 'msg' => 'Sorry, we do not have enough “' . $product->item_code . '” in stock to fulfil your order']);
//            }
            foreach ($invoice_products as $invoice_product) {
                $product = ProductsModel::find($invoice_product->product_id);
                $cost = 0;
                if ($product->type == 'stock') {
                    Inventory::decreaseInventory($invoice_product->product_id, getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'sales_order'), $invoice_details->invoice_code, $invoice_product->qty, NULL);
                }
//                $cost = AverageCostModel::getCost($invoice_product->product_id);
//                AverageCostModel::decreaseQtyCostUpdate($invoice_product->product_id, $invoice_product->qty, $cost);
                $invoice_product->cost = $cost;
                $invoice_product->status = 1;
                $invoice_product->save();
            }
        }
        if ($invoice_details->invoice_code == '') {
            $rep = User::find($request['rep_id']);
            $last_record = InvoiceModel::where('invoice_code', '!=', '')->where('status', 'O')->orderBy('id', 'desc')->first();
            $invoice_details->invoice_code = OptionModel::generateCode('O-' . substr($rep->username, 0, 3), 4, $last_record->invoice_code ?? NULL);
        } elseif ($status == 'D') {
            $last_record = InvoiceModel::where('invoice_code', '!=', '')->where('status', 'D')->orWhere('status', 'I')->orderBy('invoice_code', 'desc')->first();
            $invoice_details->invoice_code = OptionModel::generateCode('IN', 4, $last_record->invoice_code ?? NULL);
        }

        $invoice_details->assigned_user = !empty($request['staff_id']) ? ($request['staff_id']) : null;
        $invoice_details->customer_id = !empty($request['customer_id']) ? ($request['customer_id']) : 0;
        $invoice_details->remarks = $request['remarks'];

        if ($status != 'U')
            $invoice_details->status = $status;
        $invoice_details->rep_id = $request['rep_id'];
        $invoice_details->due_date = !empty($request['invoice_due_date']) ? $request['invoice_due_date'] : null;
        $invoice_details->project_id = !empty($request['project_id']) ? $request['project_id'] : null;
        $invoice_details->invoice_company = 0;
        $invoice_details->invoice_date = $request['invoice_date_created'];


        if (isset($request['quick_sell']) && $request['quick_sell'] == 1)
            $invoice_details->cash_sell = 1;

        $invoice_details->save();

        $comment = '';
        if ($status == 'O')
            $comment = '<strong>' . $request['confirm_remarks'] . '</strong>' . ' Order created by ' . Auth::user()->username;
        else if ($status == 'D')
            $comment = '<strong>' . $request['confirm_remarks'] . '</strong>' . ' Ready to dispatch created by ' . Auth::user()->username;
        else if ($status == 'I')
            $comment = '<strong>' . $request['confirm_remarks'] . '</strong>' . ' Invoice created by ' . Auth::user()->username;
        else if ($status == 'U')
            $comment = '<strong>' . $request['confirm_remarks'] . '</strong>' . ' Invoice updated by ' . Auth::user()->username;


        if ($comment != '') {
            $new_comment = new CommentsModel();
            $new_comment->comments = $comment;
            $new_comment->ref_id = $invoice_details->id;
            $new_comment->comment_type = 'IN';
            $new_comment->save();
        }

        if ($invoice_details->customer_id != NULL)
            CustomerModel::updateCustomerOutStanding($invoice_details->customer_id);

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'invoice_details' => $invoice_details]);
    }


    public function destroy(Request $request, $id)
    {
        Permission::checkPermission($request, 'DELETE_INVOICE');

        $invoice = InvoiceModel::find($id);
        if ($invoice->delete())
            return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
        else
            return response()->json(['status' => 'error', 'msg' => __('common.errors.can_not_delete_record_used_somewhere')]);

    }

    public function addInvoiceProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        parse_str($request['product_details'], $product_details);

        $invoice_id = $request['invoice_id'];
        $record_id = $request['record_product_id'];
        $qty = !empty($product_details['quantity']) ? $product_details['quantity'] : 1;

        if ($record_id == NULL) {
            $invoice_prod = InvoiceProductsModel::where('invoice_id', $invoice_id)->where('product_id', $product_details['products'])->first();
            if ($invoice_prod != NULL)
                return response()->json(['status' => 'error', 'msg' => __('You have already added this product')]);
        }

        $validator = Validator::make($product_details, [
            'quantity' => 'required',
            'unit_price' => 'required',
            //  'store_location' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }
        //   $product = ProductsModel::find($product_details['products']);
        //   $available_stock = Inventory::getProductStock($product_details['products']);
//        if ($available_stock < $qty)
//            return response()->json(['status' => 'error', 'msg' => 'Sorry, we do not have enough “' . $product->item_code . '” in stock to fulfil your order']);
//
//        if ($product->type == 'stock' && $product->qty_on_hand < $qty)
//            return response()->json(['status' => 'error', 'msg' => __('xinvoice::invoice.errors.no_stock')]);

        $invoice_prod = InvoiceProductsModel::where('invoice_id', $invoice_id)->where('id', $record_id)->first();

        if ($invoice_prod == NULL)
            $invoice_prod = new InvoiceProductsModel();

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

        $invoice_prod->invoice_id = $invoice_id;
        $invoice_prod->product_id = $product_details['products'];
        $invoice_prod->description = isset($product_details['description']) ? $product_details['description'] : '';
        $invoice_prod->qty = $qty;
        $invoice_prod->unit_price = !empty($product_details['unit_price']) ? $product_details['unit_price'] : 0;
        $invoice_prod->discount = $discount;
        $invoice_prod->discount_type = isset($product_details['discount_type']) ? $product_details['discount_type'] : 'P';
        $invoice_prod->sub_total = $sub_tot;
        $invoice_prod->store_id = 0;
        $invoice_prod->cost = 0;
        $invoice_prod->status = 0;
        $invoice_prod->save();

        $invoice = InvoiceModel::updateInvoicePrice($invoice_id);
        return response()->json(['status' => 'success', 'msg' => __('Success Details'), 'sub_total' => $invoice->sub_total]);

    }

    public function getInvoiceProducts($invoice_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        if ($invoice_id == '') {
            $products = [];
        } else {
            $products = InvoiceProductsModel::where('invoice_id', $invoice_id)->with('store_location_name')->with('product')->get();
        }

        return Datatables::of($products)
            ->editColumn('discarded', function ($products) {
            })
            ->editColumn('stock_id', function ($products) {
                return $products->product->stock_id;
            })
            ->editColumn('item', function ($products) {
                return $products->product->item_code;
            })
            ->editColumn('actions', function ($products) {
                if ($products->status != 1)
                    return "<button class='btn btn-warning btn-sm  invoice_product_edit_btn' id=''><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-sm icon-circle-cross invoice_product_delete_btn'><i class='fa fa-remove'></i></button>";
                else
                    return "<span class='label label-success'>Completed</span>";
            })
            ->editColumn('store_location', function ($products) {
                return '';
            })
            ->editColumn('sub_total', function ($products) {
                return Helper::formatPrice($products->sub_total);
            })
            ->editColumn('discount_type_show', function ($products) {
                if ($products->discount_type == 'P')
                    return 'Percentage';
                else
                    return 'Amount';
            })
            ->editColumn('quantity', function ($products) {
                return $products->qty;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function calInvoicePrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;

        if ($request['invoice_id'] != null) {
            $invoice_id = $request['invoice_id'];
            $paid_amount = InvoicePaymentModel::where('invoice_id', $invoice_id)->where('status', 1)->sum('payment_amount');
            $sub_tot = InvoiceProductsModel::where('invoice_id', $invoice_id)->sum('sub_total');
            $record = InvoiceModel::where('id', $invoice_id)->first();

            if ($request['ref_type'] == 'PAY') {

                $balance = $record['total'] - $paid_amount - $record->returned_amount;
                $record->sub_total = $sub_tot;
                $record->paid_amount = round($paid_amount, 2);
                $record->balance = round($balance, 2);
                $record->save();

                if ($record->customer_id != NULL)
                    CustomerModel::updateCustomerOutStanding($record->customer_id);
            } else {
                parse_str($request['invoice_price_details'], $prices);

                if (isset($prices['checked_vat'])) {
                    $vat = true;
                }
                if (isset($prices['checked_nbt'])) {
                    $nbt = true;
                }

                if ($record == NULL)
                    $record = new InvoiceProductsModel();

                $discount = !empty($prices['invoice_discount']) ? $prices['invoice_discount'] : 0;
                $discount_type = !empty($prices['discount_type']) ? $prices['discount_type'] : 0;

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
                if ($paid_amount != 0) {
                    $balance = $total - $paid_amount - $record->returned_amount;
                } else
                    $balance = $total -  $record->returned_amount;

                $record->sub_total = $sub_tot;
                $record->discount = $discount;
                $record->discount_type = $discount_type;
                $record->vat_amount = $vat_amount;
                $record->nbt_amount = $nbt_amount;
                $record->total = round($total, 2);
                $record->paid_amount = round($paid_amount, 2);
                $record->balance = round($balance, 2);
                $record->save();

                if ($record->customer_id != NULL)
                    CustomerModel::updateCustomerOutStanding($record->customer_id);
            }
            return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'record' => $record]);
        } else
            return 0;

    }

    public function deleteInvoiceProduct(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        parse_str($request['invoicer_price_details'], $price_details);
        $id = $request['record_id'];
        $invoice_id = $request['invoice_id'];

        InvoiceProductsModel::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
    }

    public function getSalesPayments($filter_id = '', $filter_type = '', Request $request)
    {

        Permission::checkPermission($request, 'MANAGE_PAYMENT');

        $edit_payment_permission = false;
        if (Auth::user()->can('EDIT_PAYMENT')) {
            $edit_inquiry_permission = true;
        }
        $delete_payment_permission = false;
        if (Auth::user()->can('DELETE_PAYMENT')) {
            $delete_payment_permission = true;
        }
        if ($filter_type == '') {
            if ($filter_id == '') {
                $payments = [];
            } else {
                $payments = InvoicePaymentModel::where('invoice_id', $filter_id)->with('invoice')->get();
            }

        } else {

            $payments = InvoicePaymentModel::whereHas('invoice', function ($q) use ($filter_id) {
                $q->where('customer_id', $filter_id);
            })->with('invoice')->get();

        }



        if ($payments != '') {
            return Datatables::of($payments)
                ->editColumn('actions', function ($payments) use ($edit_inquiry_permission, $delete_payment_permission) {
                    return $payments->invoice->invoice_code;
                })
                ->addColumn('invoice_code', function ($payments) {
                    return $payments->invoice->invoice_code;

                })
                ->addColumn('cheque_status', function ($payments) {
                    if ($payments->payment_method == 'cheque') {
                        if ($payments->cheque_status == 0)
                            return '<span class="text-danger"><strong>Pending</strong></span>';
                        if ($payments->cheque_status == 1)
                            return '<span class="text-success"><strong>Accepted</strong></span>';
                        if ($payments->cheque_status == 2)
                            return '<span class="text-danger"><strong>Rejected</strong></span>';
                    }
                })
                ->addColumn('cheque_bank', function ($payments) {
                    $bank = '';
                    if ($payments->bank_id != NULL) {

                        $bank = getConfigArrayValueByKey('BANKS_LIST', $payments->bank_id);
                    }
                    return $bank;


                })
                ->rawColumns(['actions', 'cheque_status'])
                ->make(true);
        }
    }

    public function saveInvoicePayment(Request $request)
    {

        Permission::checkPermission($request, 'ADD_PAYMENT');

        $invoice_id = $request['invoice_id'];
        $invoice = InvoiceModel::find($invoice_id);


        parse_str($request['payment_details'], $payment_details);

        $validator = Validator::make($payment_details, [
            'payment_method' => 'required',
            'cheque_date' => 'required_if:payment_method,cheque',
            'cheque_bank' => 'required_if:payment_method,cheque'
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        if ($payment_details['payment_method'] == 'credit') {
            $customer = CustomerModel::find($invoice->customer_id);
            if ($customer == NULL || $customer->credit_balance < $payment_details['payment_amount']) {
                return response()->json(['status' => 'error', 'msg' => 'Please check the credit balance']);
            }
        }

        if ($payment_details['payment_method'] == 'cheque') {
            $cheque = ChequeModel::saveCheque($invoice->customer_id, $payment_details['payment_amount'], $payment_details);
        }

        $payment = InvoiceModel::saveInvoicePayment($invoice_id, $payment_details['payment_amount'], $payment_details, $cheque->id ?? NULL);

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'payment' => $payment]);

    }


    public function deletePayment(Request $request)
    {
        Permission::checkPermission($request, 'DELETE_PAYMENT');

        parse_str($request['payment_details'], $payment_details);
        $id = $request['record_payment_id'];

        $invoice_id = $request['invoice_id'];
        $invoice = InvoiceModel::find($invoice_id);
        $payment = InvoicePaymentModel::where('id', $id)->first();
        $payment->status = 0;
        $payment->save();

        CustomerModel::updateCustomerOutStanding($invoice->customer_id);
        return response()->json(['status' => 'success', 'msg' => __('common.messages.record_deleted')]);
    }

    public function printPayment(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        $id = $request['record_payment_id'];
        $invoice_id = $request['invoice_id'];

        $pdf = GeneratePaymentPrintModel::generatePaymentPrint($id, $invoice_id);
        return response()->json(['status' => 'success', 'url' => $pdf]);

    }

    public function createMailModel($invoice_id = NULL, Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        $invoice = InvoiceModel::with('customer')->where('id', $invoice_id)->first();
        $email = array();
        $email['ref_id'] = $invoice->id;
        $email['mail_type'] = 'IN';

        return view('xemail_sender::create_email')
            ->with('customer_mail', $invoice->customer->email)
            ->with('customer_name', $invoice->customer->fullname)
            ->with('email', $email);

    }

    public function getCreditNoteHistoryModal($invoice_id, Request $request)
    {

        return view('xinvoice::credit_note_list_model')
            ->with('invoice_id', $invoice_id);
    }

    public function getCreditNoteHistoryList($invoice_id, Request $request)
    {

        Permission::checkPermission($request, 'MANAGE_CREDIT_NOTE');
        $credit_note = InvoiceReturnModel::where('invoice_return_code', '!=', '')->where('invoice_id', $invoice_id)->with('customer')->get();

        $edit_credit_note_permission = false;
        if (Auth::user()->can('EDIT_CREDIT_NOTE')) {
            $edit_credit_note_permission = true;
        }

        return Datatables::of($credit_note)
            ->addColumn('action', function ($credit_note) use ($edit_credit_note_permission) {
                if ($edit_credit_note_permission) {
                    return '<a class = "btn btn-info btn-xs" target="_blank" href="' . url("/invoice_return/" . $credit_note->id . "/edit") . '" id="edit_credit_note" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
                }
            })
            ->addColumn('customer', function ($credit_note) {
                if (isset($credit_note->customer))
                    return $credit_note->customer->fname;
            })
            ->editColumn('status', function ($credit_note) {
                if ($credit_note->status == 'D')
                    return 'Draft';
                elseif ($credit_note->status == 'A')
                    return 'Approved';

            })
            ->make(true);
    }

    public function getProductCreateModel(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        return view('xproduct::create_product');
    }


    public function getInvoiceList($filter_id = NULL, $filter_by = NULL, $page = NULL, Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        $invoice_type = 'all';

        if (isset($request['invoice_type']))
            $invoice_type = $request['invoice_type'];

        $invoice = InvoiceModel::where('invoice_code', '!=', '')->with('return_invoice')->with('customer')->with('user');


        if ($filter_id != null && $filter_by == 'customer' && $filter_id != '0') {

            $invoice->where('customer_id', $filter_id);
        }
        if ($page == 'payment') {
            $invoice->where('balance', '>', 0);
        }
        if ($invoice_type != 'all') {
            if ($invoice_type == 'quick')
                $invoice->where('status', 'Q');
            elseif ($invoice_type == 'orders')
                $invoice->where('status', 'O');
            elseif ($invoice_type == 'dispatch')
                $invoice->where('status', 'D');
            elseif ($invoice_type == 'invoice')
                $invoice->where('status', 'I');
        }

        $invoice = $invoice->get();

        $delete_invoice_permission = Auth::user()->can(['DELETE_INVOICE']);


        $edit_invoice_permission = false;
        if (Auth::user()->can('EDIT_INVOICE')) {
            $edit_invoice_permission = true;
        }




        return Datatables::of($invoice)
            ->addColumn('action', function ($invoice) use ($edit_invoice_permission, $page, $delete_invoice_permission) {

                $actions = '';
                if ($edit_invoice_permission) {

                    $actions .= ' <a class = "btn btn-info btn-xs" href="' . url("/invoice/" . $invoice->id . "/edit") . '" id="edit_invoice" data-original-title="" title=""><i class="fa fa-pencil"></i></a> ';
                }
                if ($delete_invoice_permission && $invoice->status != 'I' && $invoice->status != 'Q') {
                    $actions .= '&nbsp;<button  class="delete_invoice btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" title="" data-original-title="Delete " aria-describedby="tooltip934027"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
                }
                return $actions;
            })
            ->addColumn('created_by', function ($invoice) {
                return $invoice->user->username;
            })
            ->addColumn('customer', function ($invoice) {

                $full_name = '';
                if (isset($invoice->customer->company_name))
                    $full_name .= '<strong>' . $invoice->customer->company_name . '</strong><br>';
                if (isset($invoice->customer->fullname))
                    $full_name .= $invoice->customer->fullname;
                return $full_name;


            })
            ->editColumn('payment_status', function ($invoice) {

                if ($invoice->total <= $invoice->paid_amount)
                    return '<span class="text-success"><strong>Completed</strong></span>';
                elseif ($invoice->total == $invoice->balance) {

                    $count_days = Carbon::parse($invoice->invoice_date)->diffInDays(Carbon::now(), false);

                    return '<span class="text-danger"><strong>Pending</strong></span> ' . $count_days . ' Days';
                } elseif ($invoice->total != $invoice->balance) {
                    $count_days = Carbon::parse($invoice->invoice_date)->diffInDays(Carbon::now(), false);
                    return '<span class="text-primary"><strong>Partial</strong></span> '  . $count_days . ' Days';
                }

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
            ->addColumn('credit', function ($invoice) {
                return Helper::formatPrice($invoice->returned_amount);
            })
            ->editColumn('balance', function ($invoice) {
                return Helper::formatPrice($invoice->balance);
            })
            ->rawColumns(['payment_status', 'status', 'action', 'customer'])
            ->make(true);

    }

    public function generateInvoicePDF(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');

        $invoice_id = $request['invoice_id'];
        $pdf = GenerateInvoiceModel::generateInvoice($invoice_id);

        return response()->json(['status' => 'success', 'url' => $pdf]);

    }


    public function getCustomerHistoryModal($customer_id, Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        $customer = CustomerModel::findOrFail($customer_id);

        return view('xinvoice::customer_history_list')
            ->with('customer_id', $customer_id)
            ->with('customer', $customer);
    }

    public function duplicateInvoice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        $new_invoice_id = InvoiceModel::duplicateInvoice($request['invoice_id']);

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'invoice_no' => $new_invoice_id]);

    }

    public function getInvoiceStatus(Request $request)
    {
        $invoice_id = $request['invoice_id'];
        $invoice = InvoiceModel::find($invoice_id);

        $status = '';

        if ($invoice != NULL)
            $status = getConfigArrayValueByKey('INVOICE_STATUS', $invoice->status);


        return response()->json(['status' => 'success', 'invoice_code' => $invoice->invoice_code, 'invoice_status_text' => $status ?? '', 'invoice_status' => $invoice->status ?? '']);

    }

    public function getInvoiceProductsModal()
    {
        return view('xinvoice::invoice_products');
    }

    public function searchInvoiceProducts(Request $request)
    {
        $customer_id = $request['customer_id'];

        if ($customer_id == NULL)
            return response()->json(['status' => 'error', 'msg' => 'Please select the customer']);

        $product_id = $request['product_id'];

        $invoices = InvoiceModel::where('invoice_code', '!=', '')->where('customer_id', $customer_id)->where('status', 'I');
        if ($product_id != NULL) {
            $invoices->whereHas('invoiceProducts', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
        }

        $invoices = $invoices->get();

        return Datatables::of($invoices)
            ->addColumn('action', function ($invoice) {

                return '<a class = "btn btn-info btn-xs" href="' . url("/invoice_return/create?invoice_id=" . $invoice->id) . '" id="edit_invoice" data-original-title="" title=""><i class="fa fa-arrow-left"></i> Return</a>';

            })
            ->addColumn('created_by', function ($invoice) {
                return $invoice->user->username;
            })
            ->addColumn('customer', function ($invoice) {
                if (isset($invoice->customer->fullname))
                    return $invoice->customer->fullname;


            })
            ->editColumn('payment_status', function ($invoice) {

                if ($invoice->total <= $invoice->paid_amount)
                    return '<span class="text-success"><strong>Completed</strong></span>';
                elseif ($invoice->total == $invoice->balance) {

                    $count_days = Carbon::now()->diff(Carbon::parse($invoice->invoice_date));
                    return '<span class="text-danger"><strong>Pending</strong></span> ' . $count_days->format('%d Days');
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
}
