<?php

namespace Pramix\XPayment\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XCustomer\Models\CustomerModel;

class ChequeModel extends Model
{
    protected $table = 'cheque';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            $userid = 0;

            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;


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

        });
    }

    public static function saveCheque($customer_id, $payment_amount, $payment_details, $cheque_id = null)
    {
        $cheque = ChequeModel::find($cheque_id);
        if ($cheque == null)
        $cheque = new ChequeModel();
        $cheque->customer_id = $customer_id;
        $cheque->status = 0;
        $cheque->cheque_date = $payment_details['cheque_date'];
        $cheque->bank_id = $payment_details['cheque_bank'];
        $cheque->payment_date = $payment_details['payment_date'];
        $cheque->payment_ref_no = $payment_details['payment_ref_no'];
        $cheque->payment_remarks = $payment_details['payment_remarks'];
        $cheque->payment_amount = $payment_amount;
        $cheque->save();
        return $cheque;
    }


    public function customer()
    {
        return $this->hasone('Pramix\XCustomer\Models\CustomerModel','id','customer_id');
    }

    public function invoice_payment()
    {
        return $this->hasone('Pramix\XInvoice\Models\InvoicePaymentModel','cheque_id','id');
    }


}
