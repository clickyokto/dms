<?php

namespace Pramix\XGRN\Models;

use App\Scopes\BranchScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XInventory\Models\Inventory;
use Pramix\XProduct\Models\ProductsModel;

class GRNModel extends Model
{
    protected $table = 'grn';
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
        static::deleting(function($model)
        {
            $grn = GRNModel::find($model->id);
            if($grn->status != 'D')
                return false;
        });
    }


    public static function approveGRN($grn_id)
    {


        $grn = GRNModel::find($grn_id);

        $grn_products = GRNProductModel::where('grn_id', $grn_id)->where('status', 0)->get();

        foreach ($grn_products as $grn_product) {
            $product_id = $grn_product->product_id;
            $product = ProductsModel::find($grn_product->product_id);

            $product->cost= $grn_product->unit_price;
            $product->price= $grn_product->selling_price;
            $product->save();
            $cost = 0;
            if ($product->type == 'stock') {
                Inventory::increaseInventory($grn_product->product_id, getConfigArrayValueByKey('STOCK_TRANSACTION_TYPES', 'grn'), $grn->grn_code, $grn_product->delivered_qty, NULL);
            }

            $grn_product->status = 1;
            $grn_product->save();


        }

        $grn->status = 'A';
        $grn->approved_by = auth()->user()->id;
        $grn->save();


        return response()->json(['status' => 'success', 'msg' => __('common.messages.save_successfully'), 'order_details' => $grn]);

    }


    public static function storeGRN($id = NULL, $status, $remarks)
    {
        if ($id == NULL || $id == '')
            $grn_details = new GRNModel();
        else
            $grn_details = GRNModel::find($id);
        $grn_details->grn_code = '';
        $grn_details->grn_date = Carbon::now();
        $grn_details->supplier_id = 0;
        $grn_details->purchase_order_id = 0;
        $grn_details->status = $status;
        $grn_details->remarks = $remarks ?? '';

        $grn_details->save();
        return $grn_details;
    }

    public function created_user()
    {
        return $this->hasOne('Pramix\XUser\Models\User', 'id', 'created_by');
    }


    public function grnProducts()
    {
        return $this->hasMany('Pramix\XGRN\Models\GRNProductModel', 'grn_id', 'id');

    }
}
