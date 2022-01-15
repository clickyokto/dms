<?php

namespace Pramix\XInvoice\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XBranches\Models\BranchesModel;

class InvoiceReturnModel extends Model
{
    protected $table = 'invoice_return';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

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

        });
    }

    public function customer()
    {
        return $this->hasone('Pramix\XCustomer\Models\CustomerModel','id','customer_id');
    }

    public function user()
    {
        return $this->hasone('Pramix\XUser\Models\User','id','created_by');
    }

    public function invoiceReturnProducts()
    {
        return $this->hasMany('Pramix\XInvoice\Models\InvoiceReturnProductModel','invoice_return_id' , 'id');

    }


}
