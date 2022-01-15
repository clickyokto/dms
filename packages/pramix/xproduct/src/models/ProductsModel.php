<?php

namespace Pramix\XProduct\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Pramix\XBranches\Models\BranchesModel;

class ProductsModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

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

    }

    public function category()
    {
        return $this->hasOne('Pramix\XProduct\Models\ProductCategoriesModel', 'id', 'category_id');
    }

    public function discount()
    {
        return $this->hasOne('Pramix\XProduct\Models\ProductDiscountsModel', 'product_id', 'id');
    }

    public function store_location()
    {
        return $this->hasOne('Pramix\XProduct\Models\StoreLocationsModel', 'id', 'store_location');
    }
}
