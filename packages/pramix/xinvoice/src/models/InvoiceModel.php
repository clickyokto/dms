<?php

namespace Pramix\XInvoice\Models;

use App\Scopes\BranchScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XCustomer\Models\CustomerModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XUser\Models\Permission;

class InvoiceModel extends Model
{

    protected $table = 'invoice';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScopes());

        static::creating(function ($model) {
            $userid = 0;

            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
            $model->branch_id = BranchesModel::getBranchID();
        });

        static::created(function ($model) {
        });

        static::updating(function ($model) {
            $userid = 0;
            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->updated_by = $userid;

        });
        static::deleting(function ($model) {
            $invoice = InvoiceModel::find($model->id);
            if ($invoice->status == 'I' || $invoice->status == 'Q')
                return false;
        });
    }

    public function customer()
    {
        return $this->hasone('Pramix\XCustomer\Models\CustomerModel', 'id', 'customer_id');
    }

    public function rep()
    {
        return $this->hasone('Pramix\XUser\Models\User', 'id', 'ref_id');
    }

    public function return_invoice()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoiceReturnModel', 'invoice_id', 'id');

    }

    public function completed_return_invoice()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoiceReturnModel', 'invoice_id', 'id')->where('status', 'A');

    }


    public function quotation()
    {
        return $this->hasone('Pramix\XQuotation\Models\QuotationModel', 'id', 'quotation_id');
    }

    public function user()
    {
        return $this->hasone('Pramix\XUser\Models\User', 'id', 'created_by');
    }


//    public function staff_member()
//    {
//        return $this->hasone('Pramix\XStaffMember\Models\StaffMemberModel','id','assigned_user');
//    }

    public function invoiceProducts()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoiceProductsModel', 'invoice_id', 'id');

    }

    public function invoicePayment()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoicePaymentModel', 'invoice_id', 'id');

    }

    public function invoiceReturnChequeInvoicePayments()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoicePaymentModel', 'invoice_id', 'id')->whereNotNull('cheque_id')->where('cheque_status', 2);

    }

    public static function updateInvoicePrice($invoice_id)
    {
        $total = 0;
        $discount = 0;

        $sub_total = InvoiceProductsModel::where('invoice_id', $invoice_id)->sum('sub_total');

        $invoice = InvoiceModel::find($invoice_id);


        $discount = $invoice->discount;
        $discount_type = $invoice->discount_type;
        $paid_amount = $invoice->paid_amount;


        if ($discount_type == 'P') {
            $discount = ($sub_total * $discount) / 100;
        }

        $total = $sub_total - $discount;

        if ($invoice->tax_id != NULL) {
            $tax = TaxModel::find($invoice->tax_id);
            $tax = ($tax->tax_rate * $total) / 100;

            $total = $total + $tax;
        }


        $invoice = InvoiceModel::find($invoice_id);


        $invoice->sub_total = $sub_total;

        $invoice->discount = $discount;
        $invoice->discount_type = $discount_type;
        $invoice->total = $total;
        $invoice->balance = $total - $paid_amount - $invoice->returned_amount;
        $invoice->save();
        return $invoice;
    }

    public static function updateInvoicePayments($invoice_id)
    {
        $paid_amount = InvoicePaymentModel::where('invoice_id', $invoice_id)->where('status', 1)->sum('payment_amount');
        $sub_tot = InvoiceProductsModel::where('invoice_id', $invoice_id)->sum('sub_total');
        $record = InvoiceModel::find($invoice_id);
        $balance = $record['total'] - $paid_amount;
        $record->sub_total = $sub_tot;
        $record->paid_amount = round($paid_amount, 2);
        $record->balance = round($balance, 2);
        $record->save();
        CustomerModel::updateCustomerOutStanding($record->customer_id);

    }


    public static function duplicateInvoice($invoice_id)
    {
        $invoice = InvoiceModel::find($invoice_id);

        $invoice_products = InvoiceProductsModel::where('invoice_id', $invoice_id)->get();
        $invoice_code = OptionModel::generateCode('IN', 4, InvoiceModel::orderBy('id', 'desc')->first());

        $new_invoice = new InvoiceModel();
        $new_invoice->invoice_code = $invoice_code;
        $new_invoice->project_id = $invoice->project_id;
        $new_invoice->customer_id = $invoice->customer_id;
        $new_invoice->invoice_date = Carbon::now();
        $new_invoice->status = 'D';
        $new_invoice->sub_total = $invoice->sub_total;
        $new_invoice->remarks = $invoice->remarks;
        $new_invoice->discount = $invoice->discount;
        $new_invoice->vat_amount = $invoice->vat_amount;
        $new_invoice->nbt_amount = $invoice->nbt_amount;
        $new_invoice->discount_type = $invoice->discount_type;
        $new_invoice->total = $invoice->total;
        $new_invoice->balance = $invoice->total;

        $new_invoice->save();


        foreach ($invoice_products as $product) {
            $new_invoice_product = new InvoiceProductsModel();
            $new_invoice_product->invoice_id = $new_invoice->id;
            $new_invoice_product->product_id = $product->product_id;
            $new_invoice_product->description = $product->description;
            $new_invoice_product->qty = $product->qty;
            $new_invoice_product->unit_price = $product->unit_price;
            $new_invoice_product->discount = $product->discount;
            $new_invoice_product->discount_type = $product->discount_type;
            $new_invoice_product->sub_total = $product->sub_total;
            $new_invoice_product->cost = 0;
            $new_invoice_product->status = '0';
            $new_invoice_product->save();

        }

        return $new_invoice->id;

    }

    public static function saveInvoicePayment($invoice_id, $payment_amount, $payment_details, $cheque_id = NULL)
    {

        $invoice = InvoiceModel::find($invoice_id);
        $customer = CustomerModel::find($invoice->customer_id);


        $invoice_balance = $invoice->balance;
        $last_record = InvoicePaymentModel::orderBy('id', 'desc')->first();
        $payment_code = OptionModel::generateCode('IP', 4, $last_record->payment_code ?? NULL);
        $payment = new InvoicePaymentModel();
        $payment->payment_code = $payment_code;
        $payment->invoice_id = $invoice_id;
        $payment->cheque_date = $payment_details['cheque_date'];
        if ($payment_details['payment_method'] == 'cheque') {
            $payment->cheque_status = 0;
            $payment->status = 0;

            $payment->bank_id = $payment_details['cheque_bank'];
        }
        $payment->payment_date = $payment_details['payment_date'];
        $payment->payment_method = $payment_details['payment_method'];
        $payment->payment_ref_no = $payment_details['payment_ref_no'];
        $payment->payment_remarks = $payment_details['payment_remarks'];
        $payment->payment_amount = $payment_amount;
        $payment->cheque_id = $cheque_id;
        $payment->save();

        $invoice->paid_amount = $invoice->paid_amount + $payment_amount;
        $invoice->balance = $invoice->balance - $payment_amount;
        $invoice->save();


        if ($payment_details['payment_method'] == 'credit') {
            if ($customer != NULL) {
                $customer->credit_balance -= $payment_amount;
                $customer->save();
            }
        }

        if ($invoice_balance < $payment_amount) {
            if ($customer != NULL) {
                $credit = $payment_amount - $invoice_balance;
                $customer->credit_balance += $credit;
                $customer->save();
            }
        }

        if ($customer != NULL) {
            CustomerModel::updateCustomerOutStanding($invoice->customer_id);
        }

        return true;
    }


}
