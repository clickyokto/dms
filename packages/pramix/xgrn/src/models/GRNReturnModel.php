<?php

namespace Pramix\XGRN\Models;

use App\Scopes\BranchScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XBranches\Models\BranchesModel;

class GRNReturnModel extends Model
{
    protected $table = 'grn_return';
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

    public function supplier()
    {
        return $this->hasone('Pramix\XSupplier\Models\SupplierModel','id','supplier_id');
    }

    public function poReturnProducts()
    {
        return $this->hasMany('Pramix\XGRN\Models\GRNReturnProductModel','grn_return_id' , 'id');

    }
    public function user()
    {
        return $this->hasone('Pramix\XUser\Models\User','id','created_by');
    }
}
