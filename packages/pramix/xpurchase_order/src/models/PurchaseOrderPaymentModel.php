<?php

namespace Pramix\XPurchaseOrder\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XCustomer\Models\CustomerModel;

class PurchaseOrderPaymentModel extends Model
{
    protected $table = 'purchase_order_payments';
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

        });
    }

    public function purchase_order()
    {
        return $this->hasone('Pramix\XPurchaseOrder\Models\PurchaseOrderModel','id','purchase_order_id');
    }



}
