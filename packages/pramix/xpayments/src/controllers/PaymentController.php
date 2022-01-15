<?php

namespace Pramix\XPayment\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper;
use Carbon\Carbon;
use Config;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XInventory\Models\AverageCostModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoicePaymentModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XMedia\Models\MediaModel;
use Pramix\XPayment\Models\ChequeModel;
use Pramix\XProduct\Models\ProductDiscountsModel;
use Pramix\XProduct\Models\ProductsModel;
use Pramix\XProduct\Models\StoreLocationsModel;
use Pramix\XUser\Models\Permission;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        return view('xpayment::payment_list');
    }

    public function viewChequePayments(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CHEQUE_PAYMENT');
        return view('xpayment::cheque_payment_list');
    }

    public function create(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PAYMENT');

        $customer_id = $request['customer_id'] ?? '';

        $page = 'payment';
        return view('xpayment::create_payment')
            ->with('customer_id',$customer_id)
            ->with('page', $page);
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        $payments = InvoicePaymentModel::with('invoice')->where('id', $id )->first();

        return view('xpayment::edit_payment')
            ->with('customer',$payments->invoice->customer)
            ->with('user',$payments->invoice->user)
            ->with('invoice',$payments->invoice)
            ->with('payment',$payments);
    }

    public function PaymentEdit(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        parse_str($request['payment_details'], $payment_details);

        $validator = Validator::make($payment_details, [
            'payment_method_selected' => 'required',
            'cheque_bank' => 'required_if:payment_method_selected,cheque',
            'payment_amount' => 'required',
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }

        $invoice = InvoiceModel::find($payment_details['invoice_id']);
        $invoice_balance = $invoice->balance;

        $customer = CustomerModel::find($invoice->customer_id);
        $payment = InvoicePaymentModel::find($payment_details['payment_id']);

        $before_amount = $payment->payment_amount;
        $payment_amount = $payment_details['payment_amount'];



        if ($payment_details['payment_method_selected']== 'cheque') {
            $cheque = ChequeModel::saveCheque($invoice->customer_id, $payment_details['payment_amount'],$payment_details, $payment->cheque_id);
            $payment->cheque_status = 0;
            $payment->bank_id = $payment_details['cheque_bank'];
            $payment->cheque_id = $cheque->id;
        }
        $payment->status = 1;
        $payment->cheque_date = $payment_details['cheque_date'];
        $payment->payment_date = $payment_details['date'];
        $payment->payment_method = $payment_details['payment_method_selected'];
        $payment->payment_ref_no = $payment_details['payment_ref_no'];
        $payment->payment_remarks = $payment_details['payment_remarks'];
        $payment->payment_amount = $payment_details['payment_amount'];
      $payment->save();


        $defence_amount = $before_amount - $payment_amount;
        $invoice->paid_amount = $invoice->paid_amount - $defence_amount;
        $invoice->balance = $invoice->balance + $defence_amount;
        $invoice->save();


        if ($payment_details['payment_method_selected'] == 'credit') {
            if ($customer != NULL) {
                $customer->credit_balance += $defence_amount;
                $customer->save();
            }
        }

        if ($invoice->balance < $invoice_balance ) {
            if ($customer != NULL) {
                $credit = $defence_amount;
                if ($defence_amount>0)
                $customer->credit_balance -= $credit;
                else
                    $customer->credit_balance += $credit;
                $customer->save();
            }
        }

        if ($customer != NULL) {
            CustomerModel::updateCustomerOutStanding($invoice->customer_id);
        }

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully')]);


    }



    public function update(Request $request, $id)
    {
//
    }


    public function destroy($id)
    {
        //
    }

    public function saveInvoicePayment(Request $request)
    {
        Permission::checkPermission($request, 'ADD_PAYMENT');
        parse_str($request['payment_details'], $payment_details);

        $payment_amount = $payment_details['payment_amount'];
        $selected_invoices = $request['invoices_selected'];

        $validator = Validator::make($payment_details, [
            'payment_method' => 'required',
            'cheque_date' => 'required_if:payment_method,cheque'
        ]);
        if (!$validator->passes()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->all()]);
        }
        if ($selected_invoices == null) {
            return response()->json(['status' => 'error', 'msg' => 'Please select invoice']);
        }

       asort($selected_invoices);
        if ($payment_details['payment_method']== 'credit')
        {
            $customer= CustomerModel::find($request['customer_id']);
            if ($customer->credit_balance<$payment_amount)
            {
                return response()->json(['status' => 'error', 'msg' => 'Please check the credit balance']);
            }
        }

        if ($payment_details['payment_method']== 'cheque') {
$cheque = ChequeModel::saveCheque($request['customer_id'], $payment_amount,$payment_details);
        }


        foreach ($selected_invoices as $invoice_id)
        {

            $invoice_payment_amount = 0;
            $invoice = InvoiceModel::find($invoice_id);

            if ($payment_amount>= $invoice->balance)
            {
                $invoice_payment_amount = $invoice->balance;
                $payment_amount -= $invoice->balance;
            }
            else
            {
                $invoice_payment_amount = $payment_amount;
                $payment_amount = 0;
            }
            InvoiceModel::saveInvoicePayment($invoice_id, $invoice_payment_amount,$payment_details, $cheque->id ?? NULL , $payment_details['cheque_date']);

            if ($payment_amount==0)
                break;
        }

        if ($payment_amount>0)
        {
            $customer= CustomerModel::find($request['customer_id']);
            $customer->credit_balance += $payment_amount;
            $customer->save();
        }

        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully')]);

    }

    public function getInvoiceByCustomer($customer_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_INVOICE');
        if ($customer_id == '') {
            $invoice = [];
        } else {
            $invoice = InvoiceModel::where('customer_id', $customer_id)->with('customer')->get();
        }
        $edit_invoice_permission = false;
        if (Auth::user()->can('EDIT_INVOICE')) {
            $edit_invoice_permission = true;
        }
        return Datatables::of($invoice)
            ->addColumn('action', function ($invoice) use ($edit_invoice_permission) {
                if ($edit_invoice_permission) {
                    return '<a class = "btn btn-info btn-xs" href="' . url("/invoice/" . $invoice->id . "/edit") . '" id="edit_invoice" data-original-title="" title=""><i class="fa fa-pencil"></i></a> ';
                }
            })
            ->addColumn('created_by', function ($invoice) {
                return $invoice->user->username;
            })
            ->addColumn('customer', function ($invoice) {
                if (isset($invoice->customer->fullname))
                    return $invoice->customer->fullname;
            })
            ->editColumn('payment_status', function ($invoice) {

                if ($invoice->total == $invoice->paid_amount)
                    return 'Completed';
                elseif ($invoice->total == $invoice->balance)
                    return 'Pending';
                elseif ($invoice->total != $invoice->balance)
                    return 'Partial';
            })
            ->editColumn('status', function ($invoice) {
                if ($invoice->status == 'D')
                    return 'Draft';
                elseif ($invoice->status == 'A')
                    return 'Completed';
                elseif ($invoice->status == 'C')
                    return 'Cancelled';

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
            ->make(true);
    }

    public function getPaymentList($invoice_id = '', Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        if ($invoice_id == '') {
            $payments = InvoicePaymentModel::with('invoice')->get();
        } else {
            $payments = InvoicePaymentModel::where('invoice_id', $invoice_id)->get();
        }
            return Datatables::of($payments)
                ->editColumn('action', function ($payments) {
                        return "<button class='btn btn-warning btn-sm payment-view-button fa fa-eye' aria-hidden='true'></button>
                                <a href=". url("/payment/" . $payments->id . "/edit") . "><button class='btn btn-blue btn-sm fas fa-edit payment-edit-button'></button></a>";

                })
                ->editColumn('status', function ($payments) {
                    if ($payments->status != 0)
                        return '<span class="text-success"><strong>Completed</strong></span>';
                    else
                        return '<span class="text-danger"><strong>Cancelled</strong></span>';
                })
                ->addColumn('customer', function ($payments) {
                    if (isset($payments->invoice->customer->fullname))
                        return $payments->invoice->customer->fullname;
                })
                ->addColumn('invoice_code', function ($payments) {
                    $invoice_code = $payments->invoice->invoice_code ?? '';
                    return $invoice_code;
                })
                ->addColumn('customer_mobile', function ($payments) {
                    return $payments->invoice->customer->mobile ?? '';
                })
                ->addColumn('invoice_id', function ($payments) {
                    return $payments->invoice->id;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

    public function getChequePaymentsList(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_CHEQUE_PAYMENT');
       $cheques = ChequeModel::with('invoice_payment')->get();
        $approve_or_reject_cheque_payment_permission = false;
        if (Auth::user()->can('MANAGE_CHEQUE_PAYMENT')) {
            $approve_or_reject_cheque_payment_permission = true;
        }

        return Datatables::of($cheques)
            ->editColumn('action', function ($payments) use ($approve_or_reject_cheque_payment_permission) {
                $approve = '';
                $delete = '';
                if ($approve_or_reject_cheque_payment_permission && $payments->status == 0) {

                    $approve = '<button class="btn btn-success btn-sm payment-approve-button fa fa-check" aria-hidden="true"></button>';

                    $delete = '<button class="btn btn-danger btn-sm payment-delete-button fa fa-trash" aria-hidden="true"></button>';
                }
                return $approve . ' ' . $delete;
            })
            ->editColumn('status', function ($payments) {
                if ($payments->status == 0)
                    return '<span class="text-default"><strong>Pending</strong></span>';
                elseif ($payments->status == 2)
                    return '<span class="text-danger"><strong>Reject</strong></span>';
                else
                    return '<span class="text-success"><strong>Cleared</strong></span>';
            })

            ->addColumn('customer', function ($payments) {
                return $payments->customer->company_name;
            })
            ->addColumn('invoice', function ($payments) {
                return '<a href="'.url('invoice/'.$payments->invoice_payment->invoice_id).'/edit" target="_blank">'.$payments->invoice_payment->invoice->invoice_code.'</a>';
            })
            ->addColumn('customer_mobile', function ($payments) {
                return $payments->customer->mobile;
            })

            ->rawColumns(['action', 'status','invoice'])
            ->make(true);
    }

    public function rejectPayment(Request $request)
    {
        $cheque_id  = $request['cheque_id'];

        $cheque = ChequeModel::find($cheque_id);
        $cheque->status = 2;
        $cheque->save();

        $payments = InvoicePaymentModel::where('cheque_id', $cheque->id)->get();

        foreach($payments as $payment)
        {
            $payment->status = 0;
            $payment->cheque_status = 2;
            $payment->save();
            InvoiceModel::updateInvoicePayments($payment->invoice_id);
        }

        return response()->json(['status' => 'success', 'msg' => 'Cheque rejected']);
    }

    public function approvePayment(Request $request)
    {
        $cheque_id  = $request['cheque_id'];

        $cheque = ChequeModel::find($cheque_id);
        $cheque->status = 1;
        $cheque->save();

        $payments = InvoicePaymentModel::where('cheque_id', $cheque->id)->get();

        foreach($payments as $payment)
        {
            $payment->status = 1;
            $payment->cheque_status = 1;
            $payment->save();
            InvoiceModel::updateInvoicePayments($payment->invoice_id);
        }


        return response()->json(['status' => 'success', 'msg' =>__('common.messages.save_successfully')]);
    }


    public function viewPayment(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        $payments = InvoicePaymentModel::with('invoice')->where('id', $request['id'] )->first();

        return view('xpayment::payment_view')
            ->with('customer',$payments->invoice->customer)
            ->with('user',$payments->invoice->user)
            ->with('invoice',$payments->invoice)
            ->with('payments',$payments);
    }

    public function calPaymentePrice(Request $request)
    {
        Permission::checkPermission($request, 'MANAGE_PAYMENT');
        $vat = false;
        $nbt = false;
        $vat_amount = 0;
        $nbt_amount = 0;
        $invoice_id = $request['invoice_id'];

        if ($invoice_id != null) {

            $paid_amount = InvoicePaymentModel::where('invoice_id', $invoice_id)->where('status', 1)->sum('payment_amount');
            $sub_tot = InvoiceProductsModel::where('invoice_id', $invoice_id)->sum('sub_total');
            $record = InvoiceModel::where('id', $invoice_id)->first();


                if ($record['vat_amount']!=0) {
                    dd($record['vat_amount']);
                    $vat = true;
                }
                if ($record['nbt_amount']!=0) {
                    dd($record['nbt_amount']);
                    $nbt = true;
                }

                if ($record == NULL)
                    $record = new InvoiceProductsModel();

                $discount = $record['discount'];
                $discount_type = $record['discount_type'];;


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
                    $balance = $total - $paid_amount;
                } else
                    $balance = $total;

                $record->sub_total = $sub_tot;
                $record->discount = $discount;
                $record->discount_type = $discount_type;
                $record->vat_amount = $vat_amount;
                $record->nbt_amount = $nbt_amount;
                $record->total = round($total, 2);
                $record->paid_amount = round($paid_amount, 2);
                $record->balance = round($balance, 2);
                $record->save();

                CustomerModel::updateCustomerOutStanding($record->customer_id);

            return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'record' => $record]);
        } else
            return 0;

    }


}
