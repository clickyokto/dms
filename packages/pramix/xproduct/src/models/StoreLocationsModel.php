<?php

namespace Pramix\XProduct\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Pramix\XBranches\Models\BranchesModel;

class StoreLocationsModel extends Model
{
    protected $table = 'product_store_locations';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

//        static::addGlobalScope(new BranchScopes());

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
}
