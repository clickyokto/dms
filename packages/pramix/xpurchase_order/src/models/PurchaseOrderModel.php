<?php

namespace Pramix\XPurchaseOrder\Models;

use App\Scopes\BranchScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XGeneral\Models\OptionModel;
use Pramix\XSupplier\Models\SupplierModel;
use Pramix\XUser\Models\Permission;

class PurchaseOrderModel extends Model
{

    protected $table = 'purchase_order';
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

    public function supplier()
    {
        return $this->hasone('Pramix\XSupplier\Models\SupplierModel','id','supplier_id');
    }

    public function quotation()
    {
        return $this->hasone('Pramix\XQuotation\Models\QuotationModel','id','quotation_id');
    }

    public function user()
    {
        return $this->hasone('Pramix\XUser\Models\User','id','created_by');
    }


//    public function staff_member()
//    {
//        return $this->hasone('Pramix\XStaffMember\Models\StaffMemberModel','id','assigned_user');
//    }

    public function purchase_orderProducts()
    {
        return $this->hasMany('Pramix\XPurchaseOrder\Models\PurchaseOrderProductsModel','purchase_order_id' , 'id');

    }
    public function purchase_orderPayment()
    {
        return $this->hasMany('Pramix\XPurchaseOrder\Models\PurchaseOrderPaymentModel','purchase_order_id' , 'id');

    }

    public static function updatePurchaseOrderPrice($purchase_order_id)
    {
        $total = 0;
        $discount = 0;

        $sub_total = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->sum('sub_total');

        $purchase_order = PurchaseOrderModel::find($purchase_order_id);


        $discount = $purchase_order->discount;
        $discount_type = $purchase_order->discount_type;
        $paid_amount = $purchase_order->paid_amount;


        if ($discount_type == 'P') {
            $discount = ($sub_total * $discount) / 100;
        }

        $total = $sub_total - $discount;

        if ($purchase_order->tax_id != NULL) {
            $tax = TaxModel::find($purchase_order->tax_id);
            $tax = ($tax->tax_rate * $total) / 100;

            $total = $total + $tax;
        }


        $purchase_order = PurchaseOrderModel::find($purchase_order_id);


        $purchase_order->sub_total = $sub_total;

        $purchase_order->discount = $discount;
        $purchase_order->discount_type = $discount_type;
        $purchase_order->total = $total;
        $purchase_order->balance = $total - $paid_amount;
        $purchase_order->save();
        return $purchase_order;
    }


    public static function duplicatePurchaseOrder($purchase_order_id)
    {

        $purchase_order = PurchaseOrderModel::find($purchase_order_id);

        $purchase_order_products = PurchaseOrderProductsModel::where('purchase_order_id', $purchase_order_id)->get();
        $purchase_order_code = OptionModel::generateCode('IN', 4, PurchaseOrderModel::orderBy('id', 'desc')->first());

        $new_purchase_order = new PurchaseOrderModel();
        $new_purchase_order->purchase_order_code = $purchase_order_code;
        $new_purchase_order->project_id = $purchase_order->project_id;
        $new_purchase_order->supplier_id = $purchase_order->supplier_id;
        $new_purchase_order->purchase_order_date = Carbon::now();
        $new_purchase_order->status = 'D';
        $new_purchase_order->sub_total = $purchase_order->sub_total;
        $new_purchase_order->remarks = $purchase_order->remarks;
        $new_purchase_order->discount = $purchase_order->discount;
        $new_purchase_order->vat_amount = $purchase_order->vat_amount;
        $new_purchase_order->nbt_amount = $purchase_order->nbt_amount;
        $new_purchase_order->discount_type = $purchase_order->discount_type;
        $new_purchase_order->total = $purchase_order->total;
        $new_purchase_order->balance = $purchase_order->total;

        $new_purchase_order->save();


        foreach ($purchase_order_products as $product) {
            $new_purchase_order_product = new PurchaseOrderProductsModel();
            $new_purchase_order_product->purchase_order_id = $new_purchase_order->id;
            $new_purchase_order_product->product_id = $product->product_id;
            $new_purchase_order_product->description = $product->description;
            $new_purchase_order_product->qty = $product->qty;
            $new_purchase_order_product->unit_price = $product->unit_price;
            $new_purchase_order_product->discount = $product->discount;
            $new_purchase_order_product->discount_type = $product->discount_type;
            $new_purchase_order_product->sub_total = $product->sub_total;
            $new_purchase_order_product->cost = 0;
            $new_purchase_order_product->status = '0';
            $new_purchase_order_product->save();
        }
        return  $new_purchase_order->id;
    }

    public static function savePurchaseOrderPayment($purchase_order_id, $payment_amount , $payment_details)
    {

        $purchase_order = PurchaseOrderModel::find($purchase_order_id);
        $last_record = PurchaseOrderPaymentModel::orderBy('id', 'desc')->first();
        $payment_code = OptionModel::generateCode('BP', 4, $last_record->payment_code ?? NULL);
        $payment = new PurchaseOrderPaymentModel();
        $payment->payment_code = $payment_code;
        $payment->purchase_order_id = $purchase_order_id;
        $payment->payment_date = $payment_details['payment_date'];
        $payment->payment_method = $payment_details['payment_method'];
        $payment->payment_ref_no = $payment_details['payment_ref_no'];
        $payment->payment_remarks = $payment_details['payment_remarks'];
        $payment->payment_amount = $payment_amount;
        $payment->save();

        $purchase_order->paid_amount = $purchase_order->paid_amount+ $payment_amount;
        $purchase_order->balance = $purchase_order->balance-$payment_amount;
        $purchase_order->save();

        if ($payment_details['payment_method']== 'debit')
        {
            $supplier= SupplierModel::find($purchase_order->supplier_id);
            $supplier->debit -= $payment_amount;

        }

        SupplierModel::updateSupplierOutStanding($purchase_order->supplier_id);

        return true;
    }
}
