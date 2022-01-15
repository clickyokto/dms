<?php

namespace Pramix\XInventory\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Pramix\XBranches\Models\BranchesModel;
use Pramix\XProduct\Models\ProductsModel;


class Inventory extends Model
{
    protected $table = 'inventory';
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

    public function product()
    {
        return $this->hasOne('Pramix\XProduct\Models\ProductsModel', 'id', 'product_id');

    }

    public function store()
    {
        return $this->hasOne('Pramix\XProduct\Models\StoreLocationsModel', 'id', 'store_id');

    }

    public function user()
    {
        return $this->hasOne('Pramix\XUser\Models\User', 'id', 'created_by');

    }

    public static function increaseInventory($product_id, $transaction_type_id, $order_number, $qty, $store_id = NULL)
    {

        $last_record = static::where('product_id', $product_id)->where('active_status', 1)->latest('id')->where('store_id', $store_id)->first();



        if ($last_record == null) {
            $qty_before = 0;
        } else {
            $qty_before = $last_record->qty_after;
        }
        $inventory = new static();
        //  $inventory = new Inventory();
        $inventory->product_id = $product_id;
        $inventory->transaction_type_id = $transaction_type_id;
        $inventory->date = date('Y-m-d');
        $inventory->order_number = $order_number;
        $inventory->qty_before = $qty_before;
        $inventory->qty = $qty;
        $inventory->qty_after = $qty_before + $qty;
        $inventory->type = 'I';
        $inventory->active_status = 1;
        $inventory->store_id = NULL;
        $inventory->save();

        if ($last_record != null) {
            $last_record->active_status = 0;
            $last_record->save();
        }

        self::updateProductQtyOnHand($product_id);

    }

    public static function decreaseInventory($product_id, $transaction_type_id, $order_number = '', $qty, $store_id = NULL)
    {
        $last_record = static::where('product_id', $product_id)->latest('id')->where('store_id', $store_id)->where('active_status', 1)->first();

        if ($last_record == null) {
            $qty_before = 0;
        } else {
            $qty_before = $last_record->qty_after;

        }
        $inventory = new static();
        //  $inventory = new Inventory();
        $inventory->product_id = $product_id;
        $inventory->transaction_type_id = $transaction_type_id;
        $inventory->date = date('Y-m-d');
        $inventory->order_number = $order_number;
        $inventory->qty_before = $qty_before;
        $inventory->qty = $qty;
        $inventory->active_status = 1;
        $inventory->store_id = NULL;
        if ($last_record == null)
            $inventory->qty_after = 0;
        else
            $inventory->qty_after = $qty_before - $qty;

        $inventory->type = 'D';
        $inventory->save();

        if ($last_record != null) {
            $last_record->active_status = 0;
            $last_record->save();
        }


        self::updateProductQtyOnHand($product_id);


        return true;
    }

    public static function updateProductQtyOnHand($product_id)
    {
        $product_count = static::where('product_id', $product_id)->where('active_status', 1)->sum('qty_after');
        $product = ProductsModel::find($product_id);
        $product->qty_on_hand = $product_count ?? 0;
        $product->save();
        return true;

    }

    public static function stockAdjustment($product_id, $transaction_type_id, $order_number = '', $qty, $store_id = NULL)
    {

        $product = ProductsModel::find($product_id);

        $available_stock = Inventory::getProductStock($product->id, $store_id);

//        if ($product->type == 'stock' && $available_stock < $invoice_product->qty)
        if ($available_stock <= $qty)
            self::increaseInventory($product_id, $transaction_type_id, $order_number = '', $qty -$available_stock, $store_id);
        elseif ($available_stock > $qty)
            self::decreaseInventory($product_id, $transaction_type_id, $order_number = '', $available_stock - $qty, $store_id);

    }

    public static function getProductStock($product_id)
    {
        $stock = static::where('product_id', $product_id)->latest('id')->where('active_status', 1)->first();
        if($stock==NULL)
            return 0;
        else
            return $stock->qty_after;


    }

}
