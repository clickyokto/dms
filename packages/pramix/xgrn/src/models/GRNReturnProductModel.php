<?php

namespace Pramix\XGRN\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XBranches\Models\BranchesModel;

class GRNReturnProductModel extends Model
{
    protected $table = 'grn_return_products';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();

        static::addGlobalScope(new BranchScopes());

        static::creating(function($model) {
            $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
            $model->branch_id = BranchesModel::getBranchID();
        });

        static::updating(function($model) {
            $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });
    }

    public function product()
    {
        return $this->hasOne('Pramix\XProduct\Models\ProductsModel','id','product_id');

    }

    public function store_location_name()
    {
        return $this->hasOne('Pramix\XProduct\Models\StoreLocationsModel','id','store_id');
    }


    public function supplier()
    {
        return $this->hasone('Pramix\XSupplier\Models\SupplierModel','id','supplier_id');
    }

    public function purchaseOrderProducts()
    {
        return $this->hasMany('Pramix\XGRN\Models\PurchaseOrderProductsModel','order_id' , 'id');

    }

}
