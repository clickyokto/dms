<?php

namespace Pramix\XCustomer\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoicePaymentModel;
use Pramix\XInvoice\Models\InvoiceProductsModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;

class CustomerModel extends Model
{

    protected $table = 'customer';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScopes());

        static::creating(function ($model) {
            $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
            $model->branch_id = BranchesModel::getBranchID();
        });

        static::updating(function ($model) {
            $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });

        static::deleting(function($model)
        {
            $invoice = InvoiceModel::where('customer_id',$model->id)->count();
            if($invoice != 0)
                return false;
        });
    }

    public function comments()
    {
        return $this->hasMany('Pramix\XCustomer\Models\CustomerCommentsModel', 'customer_id', 'id');
    }

    public function activeOutstandingInvoices()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoiceModel', 'customer_id', 'id')->where('invoice_code','<>', '')->whereRaw('total > paid_amount');
    }

    public function rep()
    {
        return $this->hasOne('Pramix\XUser\Models\User', 'id', 'rep_id');
    }
    public function area()
    {
        return $this->hasOne('Pramix\XGeneral\Models\AreaModel', 'id', 'area_id');
    }

    public function customerAddress()
    {
        return $this->hasMany('Pramix\XGeneral\Models\AddressModel', 'ref_id', 'id')->where('user_type', 'C')->where('address_type', 'B');
    }

    public function customerPrivateAddress()
    {
        return $this->hasOne('Pramix\XGeneral\Models\AddressModel', 'ref_id', 'id')->where('user_type', 'C')->where('address_type', 'B');
    }

    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }

    public function getCompanyFullNameAttribute()
    {
        return $this->company_name . ' ' . $this->company_branch;
    }

    public static function updateCustomerOutStanding($customer_id)
    {

        $invoices_balance_sum = InvoiceModel::where('invoice_code','!=', '')->where('customer_id',$customer_id)->sum('balance');

        //$invoice_return_sum
      //  $invoice_return_balance_sum = InvoiceReturnModel::where('customer_id',$customer_id)->where('status','A')->sum('refund');

    //    $outstanding_amount = $invoices_balance_sum - $invoice_return_balance_sum;
        $outstanding_amount = $invoices_balance_sum;

        $customer = CustomerModel::find($customer_id);

            $customer->outstanding_amount = round($outstanding_amount,2);
        $customer->save();

    }

    public static function storeCustomer($customer_details)
    {
        $customer = CustomerModel::find($customer_details['customer_id']);
        if ($customer==null)
            $customer = new CustomerModel();
        $customer->title = isset($customer_details['title']) ? $customer_details['title'] : '';
        $customer->company_name =isset($customer_details['company_name']) ? $customer_details['company_name'] : '';
        $customer->company_branch = isset($customer_details['company_branch']) ? $customer_details['company_branch'] : '';
        $customer->gender = isset($customer_details['gender']) ? $customer_details['gender'] : '';
        $customer->business_name = $customer_details['business_name'];
        $customer->customer_type = isset($customer_details['customer_type']) ? $customer_details['customer_type'] : '';
        $customer->fname = $customer_details['first_name'];
        $customer->lname = $customer_details['last_name'];
        $customer->telephone = isset($customer_details['formated_telephone']) ? $customer_details['formated_telephone'] : '';
        $customer->mobile = isset($customer_details['formated_mobile']) ? $customer_details['formated_mobile'] : '';
        $customer->fax = isset($customer_details['formated_fax']) ? $customer_details['formated_fax'] : '';
        $customer->tax_no = !empty($customer_details['tax_no']) ? $customer_details['tax_no'] : '';
        $customer->email = $customer_details['email'];
        $customer->website = $customer_details['website'] ?? '';
        $customer->remarks = $customer_details['customer_remarks'] ?? '';
        $customer->discount = floatval($customer_details['discount'] ?? 0);
        $customer->discount_type = $customer_details['discount_type'] ?? '';
        $customer->nic = $customer_details['nic'] ?? '';
        $customer->dob = NULL;
        $customer->status = 'A';
        $customer->outstanding_max_days = $customer_details['outstanding_day_limit'] ?? '';
        $customer->outstanding_limit = $customer_details['outstanding_limit'] ?? '';
        $customer->user_account_id = $customer_details['user_account_id'] ?? NULL;
    //    $customer->area_id = $customer_details['area'];
        $customer->rep_id = $customer_details['rep'];
        $customer->invoice_type = $customer_details['invoice_type'];
        $customer->save();
        return $customer;
    }
}
