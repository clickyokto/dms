<?php

namespace Pramix\XGRN\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XBranches\Models\BranchesModel;

class GRNProductModel extends Model
{
    protected $table = 'grn_product';
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

    public function product()
    {
        return $this->hasOne('Pramix\XProduct\Models\ProductsModel','id','product_id');

    }

    public function store_location_name()
    {
        return $this->hasOne('Pramix\XProduct\Models\StoreLocationsModel','id','store_id');
    }
}
